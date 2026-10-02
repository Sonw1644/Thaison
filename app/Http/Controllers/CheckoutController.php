<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;

class CheckoutController extends Controller
{
    /**
     * Hiển thị trang thanh toán đơn hàng (hỗ trợ lọc theo sản phẩm đã chọn)
     */
    public function index(Request $request)
    {
        $allCart = session()->get('cart', []);

        if (empty($allCart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống. Vui lòng chọn sản phẩm trước khi thanh toán!');
        }

        // Lọc theo các sản phẩm được chọn từ giỏ hàng
        $cart = $allCart;
        if ($request->has('selected_items')) {
            $selectedKeys = (array) $request->input('selected_items');
            $cart = array_intersect_key($allCart, array_flip($selectedKeys));

            if (empty($cart)) {
                return redirect()->route('cart.index')->with('error', 'Vui lòng tích chọn ít nhất 1 sản phẩm để tiến hành thanh toán!');
            }
        }

        $selectedItemKeys = array_keys($cart);

        $subtotal = 0;
        $totalQuantity = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $totalQuantity += $item['quantity'];
        }

        $user = Auth::user();

        return view('checkout.index', compact('cart', 'subtotal', 'totalQuantity', 'user', 'selectedItemKeys'));
    }

    /**
     * Xử lý xác nhận đặt hàng và tạo đơn cho các sản phẩm đã chọn
     */
    public function process(Request $request)
    {
        $allCart = session()->get('cart', []);

        if (empty($allCart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        // Lọc các món hàng đã chọn để thanh toán
        $selectedKeys = $request->input('selected_items', array_keys($allCart));
        if (is_string($selectedKeys)) {
            $selectedKeys = explode(',', $selectedKeys);
        }
        $cart = array_intersect_key($allCart, array_flip((array)$selectedKeys));

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Không tìm thấy sản phẩm nào được chọn để thanh toán!');
        }

        $request->validate([
            'customer_name'     => 'required|string|max:255',
            'customer_phone'    => 'required|string|max:20',
            'customer_email'    => 'nullable|email|max:255',
            'province'          => 'required|string|max:100',
            'district'          => 'required|string|max:100',
            'ward'              => 'required|string|max:100',
            'to_district_id'    => 'nullable|integer',
            'to_ward_code'      => 'nullable|string',
            'shipping_fee'      => 'nullable|numeric|min:0',
            'shipping_address'  => 'required|string|max:500',
            'payment_method'    => 'required|in:cod,banking,momo',
            'notes'             => 'nullable|string|max:1000',
        ], [
            'customer_name.required'    => 'Vui lòng nhập họ và tên người nhận hàng.',
            'customer_phone.required'   => 'Vui lòng nhập số điện thoại liên hệ.',
            'province.required'         => 'Vui lòng chọn hoặc nhập Tỉnh / Thành phố.',
            'district.required'         => 'Vui lòng chọn hoặc nhập Quận / Huyện.',
            'ward.required'             => 'Vui lòng chọn hoặc nhập Phường / Xã.',
            'shipping_address.required' => 'Vui lòng nhập địa chỉ chi tiết (số nhà, tên đường).',
            'payment_method.required'   => 'Vui lòng chọn phương thức thanh toán.',
        ]);

        try {
            $shippingFee = (int) $request->input('shipping_fee', 0);

            $order = DB::transaction(function () use ($request, $allCart, $cart, $selectedKeys, $shippingFee) {
                // Tính toán tổng tiền sản phẩm
                $itemsTotal = 0;
                foreach ($cart as $item) {
                    $itemsTotal += $item['price'] * $item['quantity'];
                }
                $totalAmount = $itemsTotal + $shippingFee;

                // Sinh mã đơn hàng duy nhất: TS-YYYYMMDD-XXXX
                $orderCode = 'TS-' . date('Ymd') . '-' . strtoupper(Str::random(5));

                // Tạo bản ghi Đơn hàng
                $order = Order::create([
                    'order_code'        => $orderCode,
                    'user_id'           => Auth::id(),
                    'customer_name'     => $request->customer_name,
                    'customer_phone'    => $request->customer_phone,
                    'customer_email'    => $request->customer_email ?? (Auth::user()->email ?? null),
                    'province'          => $request->province,
                    'district'          => $request->district,
                    'ward'              => $request->ward,
                    'to_district_id'    => $request->to_district_id ? (int)$request->to_district_id : null,
                    'to_ward_code'      => $request->to_ward_code ? (string)$request->to_ward_code : null,
                    'shipping_address'  => $request->shipping_address,
                    'notes'             => $request->notes,
                    'payment_method'    => $request->payment_method,
                    'payment_status'    => 'unpaid',
                    'status'            => 'pending',
                    'shipping_status'   => 'not_shipped',
                    'ghn_total_fee'     => $shippingFee,
                    'total_amount'      => $totalAmount,
                ]);

                // Tạo các mục sản phẩm trong đơn và cập nhật tồn kho
                foreach ($cart as $item) {
                    $itemSubtotal = $item['price'] * $item['quantity'];

                    OrderItem::create([
                        'order_id'           => $order->id,
                        'product_id'         => $item['product_id'] ?? null,
                        'variant_id'         => $item['variant_id'] ?? null,
                        'product_name'       => $item['name'],
                        'variant_color_name' => $item['color_name'] ?? null,
                        'thumbnail'          => $item['thumbnail'] ?? null,
                        'price'              => $item['price'],
                        'quantity'           => $item['quantity'],
                        'subtotal'           => $itemSubtotal,
                    ]);

                    // Trừ tồn kho nếu có
                    if (!empty($item['variant_id'])) {
                        $variant = ProductVariant::find($item['variant_id']);
                        if ($variant && $variant->stock >= $item['quantity']) {
                            $variant->decrement('stock', $item['quantity']);
                        }
                    }
                    if (!empty($item['product_id'])) {
                        $product = Product::find($item['product_id']);
                        if ($product && $product->stock >= $item['quantity']) {
                            $product->decrement('stock', $item['quantity']);
                        }
                    }
                }

                // Chỉ xóa các món hàng ĐÃ ĐƯỢC CHỌN thanh toán khỏi giỏ hàng
                $remainingCart = array_diff_key($allCart, array_flip((array)$selectedKeys));
                session()->put('cart', $remainingCart);

                return $order;
            });

            // Phân luồng thanh toán theo phương thức
            if ($request->payment_method === 'momo') {
                \App\Models\PaymentTransaction::create([
                    'order_id' => $order->id,
                    'gateway'  => 'momo',
                    'amount'   => $order->total_amount,
                    'status'   => 'pending',
                ]);

                return redirect()->route('orders.momo.start', $order);
            }

            // Ghi nhận transaction cho COD hoặc Banking
            \App\Models\PaymentTransaction::create([
                'order_id' => $order->id,
                'gateway'  => $request->payment_method,
                'amount'   => $order->total_amount,
                'status'   => 'pending',
                'message'  => $request->payment_method === 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản VietQR',
            ]);

            // Tự động tạo đơn hàng GHN cho COD / Banking nếu có mã quận huyện và phường xã
            if ($order->to_district_id && $order->to_ward_code) {
                try {
                    $order->load('items.product');
                    $ghnOrderService = app(\App\Services\GHNOrderService::class);
                    $ghnResponse = $ghnOrderService->create($order, $order->payment_method === 'banking');
                    if (isset($ghnResponse['code']) && $ghnResponse['code'] == 200 && !empty($ghnResponse['data']['order_code'])) {
                        $order->update([
                            'ghn_order_code'  => $ghnResponse['data']['order_code'],
                            'shipping_status' => 'ready_to_pick',
                        ]);
                    }
                } catch (\Throwable $ghnEx) {
                    \Illuminate\Support\Facades\Log::warning('GHN create order error: ' . $ghnEx->getMessage());
                }
            }

            return redirect()->route('checkout.success', $order->order_code)
                ->with('success', 'Chúc mừng bạn! Đơn hàng ' . $order->order_code . ' đã được tạo thành công.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Có lỗi xảy ra trong quá trình tạo đơn hàng: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị trang hoàn tất thanh toán / hóa đơn đơn hàng
     */
    public function success($order_code)
    {
        $order = Order::with(['items', 'user'])
            ->where('order_code', $order_code)
            ->firstOrFail();

        // Kiểm tra quyền truy cập (nếu đơn có user_id và đang đăng nhập)
        if ($order->user_id && Auth::check() && Auth::id() !== $order->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        return view('checkout.success', compact('order'));
    }

    /**
     * Xác nhận đã chuyển khoản ngân hàng thành công
     */
    public function confirmPayment(Request $request, $order_code)
    {
        $order = Order::where('order_code', $order_code)->firstOrFail();

        // Kiểm tra quyền
        if ($order->user_id && Auth::check() && Auth::id() !== $order->user_id && Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Không có quyền thao tác.'], 403);
        }

        $order->update([
            'payment_status' => 'paid',
            'status'         => 'shipping',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'              => true,
                'message'              => 'Đã xác nhận thanh toán thành công!',
                'payment_status'       => $order->payment_status,
                'payment_status_label' => $order->payment_status_label,
                'status'               => $order->status,
                'status_label'         => $order->status_label,
            ]);
        }

        return back()->with('success', 'Đã xác nhận thanh toán thành công cho đơn hàng ' . $order->order_code . '!');
    }

    /**
     * API kiểm tra trạng thái đơn hàng (dành cho polling tự động)
     */
    public function checkStatus(Request $request, $order_code)
    {
        $order = Order::where('order_code', $order_code)->firstOrFail();

        // Nếu người dùng mở trực tiếp đường dẫn này trên trình duyệt (thay vì gọi qua fetch ngầm)
        if ($request->header('Sec-Fetch-Dest') === 'document' || (!$request->ajax() && !$request->wantsJson() && $request->acceptsHtml())) {
            return redirect()->route('checkout.success', $order->order_code);
        }

        return response()->json([
            'order_code'           => $order->order_code,
            'status'               => $order->status,
            'status_label'         => $order->status_label,
            'payment_status'       => $order->payment_status,
            'payment_status_label' => $order->payment_status_label,
            'is_paid'              => $order->payment_status === 'paid',
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
