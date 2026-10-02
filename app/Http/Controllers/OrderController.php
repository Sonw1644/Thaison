<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;

class OrderController extends Controller
{
    /**
     * Danh sách lịch sử đơn hàng của người dùng hiện tại
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Order::with('items')
            ->where('user_id', $user->id)
            ->latest();

        // Lọc theo trạng thái đơn hàng nếu có
        if ($request->filled('status') && $request->status !== 'all') {
            if (in_array($request->status, ['pending', 'cod_ordered'])) {
                $query->whereIn('status', ['pending', 'cod_ordered']);
            } elseif ($request->status === 'shipping') {
                $query->whereIn('status', ['shipping', 'delivering', 'processing']);
            } elseif ($request->status === 'completed') {
                $query->whereIn('status', ['completed']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // Tìm kiếm theo mã đơn
        if ($request->filled('search')) {
            $query->where('order_code', 'like', '%' . trim($request->search) . '%');
        }

        $orders = $query->paginate(10)->withQueryString();

        // Đếm số lượng theo các trạng thái chính
        $counts = [
            'all'        => Order::where('user_id', $user->id)->count(),
            'pending'    => Order::where('user_id', $user->id)->whereIn('status', ['pending', 'cod_ordered'])->count(),
            'shipping'   => Order::where('user_id', $user->id)->whereIn('status', ['shipping', 'delivering', 'processing'])->count(),
            'completed'  => Order::where('user_id', $user->id)->whereIn('status', ['completed'])->count(),
            'cancelled'  => Order::where('user_id', $user->id)->where('status', 'cancelled')->count(),
        ];

        return view('orders.index', compact('orders', 'counts'));
    }

    /**
     * Chi tiết đơn hàng của người dùng
     */
    public function show($order_code)
    {
        $order = Order::with(['items.product', 'items.variant'])
            ->where('user_id', Auth::id())
            ->where('order_code', $order_code)
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    /**
     * Khách hàng tự hủy đơn hàng khi đơn còn ở trạng thái Chờ xử lý (pending)
     */
    public function cancel(Order $order)
    {
        // Kiểm tra quyền sở hữu
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thao tác trên đơn hàng này.');
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể hủy đơn hàng khi đang ở trạng thái "Chờ xử lý". Đơn hàng đã được xác nhận hoặc đang vận chuyển không thể hủy trực tiếp.');
        }

        // Cập nhật trạng thái sang cancelled
        $order->update([
            'status' => 'cancelled'
        ]);

        // Hoàn trả lại số lượng tồn kho
        foreach ($order->items as $item) {
            if ($item->variant_id) {
                $variant = ProductVariant::find($item->variant_id);
                if ($variant) {
                    $variant->increment('stock', $item->quantity);
                }
            }
            if ($item->product_id) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }
        }

        return back()->with('success', 'Đơn hàng ' . $order->order_code . ' đã được hủy thành công!');
    }
}
