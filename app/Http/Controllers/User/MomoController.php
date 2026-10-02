<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Services\GHNOrderService;
use App\Services\MomoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MomoController extends Controller
{
    /**
     * Bắt đầu thanh toán đơn hàng qua MoMo
     */
    public function start(Order $order, MomoService $momo)
    {
        if (Auth::check() && $order->user_id && $order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thao tác trên đơn hàng này.');
        }

        return $this->redirectToMomo($order, $this->newTransaction($order), $momo);
    }

    /**
     * Thanh toán lại cho đơn hàng chưa thanh toán
     */
    public function payAgain(Order $order, MomoService $momo)
    {
        if (Auth::check() && $order->user_id && $order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thao tác trên đơn hàng này.');
        }

        return $this->redirectToMomo($order, $this->newTransaction($order), $momo);
    }

    /**
     * Xử lý khi khách hàng hoàn tất hoặc hủy thanh toán và quay về website
     */
    public function callback(Request $request, GHNOrderService $ghnOrders, MomoService $momo)
    {
        Log::info('MoMo callback received', [
            'payload'       => $request->except('signature'),
            'has_signature' => $request->has('signature'),
        ]);

        if (!$momo->isValidSuccessfulResponse($request->all())) {
            Log::warning('MoMo callback rejected', [
                'result_code'     => $request->input('resultCode'),
                'order_id'        => $request->input('orderId'),
                'signature_valid' => $momo->isValidResponse($request->all()),
            ]);

            if ($momo->isValidResponse($request->all())) {
                $this->markFailed($request->all(), $momo);
            }

            return redirect()->route('orders.index')->with('error', 'Giao dịch MoMo không thành công hoặc đã bị hủy.');
        }

        $result = $this->completePayment($request->all(), $ghnOrders, $momo);
        $message = in_array($result, ['created', 'already_created'], true)
            ? 'Thanh toán MoMo thành công! Vận đơn GHN đã được khởi tạo.'
            : 'Thanh toán MoMo thành công! Đơn hàng đang chờ xử lý vận đơn GHN.';

        $orderId = $momo->orderId($request->all());
        $order = $orderId ? Order::find($orderId) : null;

        if ($order) {
            return redirect()->route('checkout.success', $order->order_code)->with('success', $message);
        }

        return redirect()->route('orders.index')->with('success', $message);
    }

    /**
     * Nhận thông báo IPN trực tiếp từ MoMo Server sang Server (ngầm)
     */
    public function ipn(Request $request, GHNOrderService $ghnOrders, MomoService $momo)
    {
        Log::info('MoMo IPN received', [
            'payload'       => $request->except('signature'),
            'has_signature' => $request->has('signature'),
        ]);

        if ($momo->isValidSuccessfulResponse($request->all())) {
            $this->completePayment($request->all(), $ghnOrders, $momo);
        } elseif ($momo->isValidResponse($request->all())) {
            $this->markFailed($request->all(), $momo);
        }

        return response()->json(['message' => 'Received']);
    }

    /**
     * Tạo mới một bản ghi giao dịch thanh toán
     */
    private function newTransaction(Order $order): PaymentTransaction
    {
        return PaymentTransaction::create([
            'order_id' => $order->id,
            'gateway'  => 'momo',
            'amount'   => $order->total_amount ?? $order->total_price,
            'status'   => 'pending',
        ]);
    }

    /**
     * Lấy đường dẫn thanh toán từ MoMo và chuyển hướng người dùng
     */
    private function redirectToMomo(Order $order, PaymentTransaction $transaction, MomoService $momo)
    {
        $result = $momo->createPayment($order, $transaction);

        return isset($result['payUrl'])
            ? redirect($result['payUrl'])
            : redirect()->route('orders.index')->with('error', 'Không thể kết nối tới MoMo: ' . ($result['message'] ?? 'Vui lòng thử lại sau.'));
    }

    /**
     * Xác nhận thanh toán thành công và tạo vận đơn GHN
     */
    private function completePayment(array $payload, GHNOrderService $ghnOrders, MomoService $momo): string
    {
        $result = DB::transaction(function () use ($payload, $momo) {
            $transaction = PaymentTransaction::where('gateway', 'momo')
                ->where('gateway_order_id', $payload['orderId'] ?? '')
                ->lockForUpdate()
                ->first();

            if (!$transaction) {
                return 'invalid';
            }

            $order = Order::lockForUpdate()->find($transaction->order_id);
            if (!$order) {
                return 'invalid';
            }

            if ($order->ghn_order_code) {
                return 'already_created';
            }

            if ($order->shipping_status === 'processing') {
                return 'processing';
            }

            if ((int) $transaction->amount !== (int) ($payload['amount'] ?? 0)) {
                $momo->markFailed($transaction, $payload);
                return 'invalid';
            }

            $order->update([
                'payment_status'  => 'paid',
                'status'          => 'shipping',
                'shipping_status' => 'processing',
            ]);

            $momo->markPaid($transaction, $payload);

            return ['create', $order->id];
        });

        if (!is_array($result)) {
            return (string) $result;
        }

        $order = Order::with('items.product')->find($result[1]);
        if (!$order) {
            return 'failed';
        }

        try {
            $response = $ghnOrders->create($order, true);
            if (isset($response['code']) && $response['code'] === 200 && !empty($response['data']['order_code'])) {
                $order->update([
                    'status'          => 'shipping',
                    'ghn_order_code'  => $response['data']['order_code'],
                    'shipping_status' => 'ready_to_pick',
                ]);
                return 'created';
            }

            Log::error('GHN order failed after MoMo payment', [
                'order_id' => $order->id,
                'response' => $response,
            ]);
            $order->update(['shipping_status' => 'pending']);
            return 'failed';
        } catch (\Throwable $e) {
            Log::error('GHN exception after MoMo payment: ' . $e->getMessage());
            $order->update(['shipping_status' => 'pending']);
            return 'failed';
        }
    }

    /**
     * Ghi nhận giao dịch MoMo thất bại
     */
    private function markFailed(array $payload, MomoService $momo): void
    {
        $transaction = PaymentTransaction::where('gateway', 'momo')
            ->where('gateway_order_id', $payload['orderId'] ?? '')
            ->first();

        if ($transaction && $transaction->status !== 'paid') {
            $momo->markFailed($transaction, $payload);
        }
    }
}
