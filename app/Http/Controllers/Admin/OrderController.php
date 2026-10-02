<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;

class OrderController extends Controller
{
    /**
     * Danh sách toàn bộ đơn hàng trong hệ thống (Dành cho Admin)
     */
     public function index(Request $request)
     {
         $query = Order::with(['items', 'user'])->latest();

         // Lọc theo trạng thái đơn hàng
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

         // Lọc theo trạng thái thanh toán
         if ($request->filled('payment_status') && $request->payment_status !== 'all') {
             $query->where('payment_status', $request->payment_status);
         }

         // Tìm kiếm (Mã đơn, Tên người nhận, SĐT)
         if ($request->filled('search')) {
             $search = trim($request->search);
             $query->where(function ($q) use ($search) {
                 $q->where('order_code', 'like', "%{$search}%")
                   ->orWhere('customer_name', 'like', "%{$search}%")
                   ->orWhere('customer_phone', 'like', "%{$search}%")
                   ->orWhere('customer_email', 'like', "%{$search}%");
             });
         }

         $orders = $query->paginate(12)->withQueryString();

         // Thống kê số lượng đơn theo các trạng thái chính
         $counts = [
             'all'        => Order::count(),
             'pending'    => Order::whereIn('status', ['pending', 'cod_ordered'])->count(),
             'shipping'   => Order::whereIn('status', ['shipping', 'delivering', 'processing'])->count(),
             'completed'  => Order::whereIn('status', ['completed'])->count(),
             'cancelled'  => Order::where('status', 'cancelled')->count(),
         ];

         return view('admin.orders.index', compact('orders', 'counts'));
     }

     /**
      * Chi tiết đơn hàng trong trang quản trị
      */
     public function show(Order $order)
     {
         $order->load(['items.product', 'items.variant', 'user']);
         return view('admin.orders.show', compact('order'));
     }

     /**
      * Cập nhật trạng thái đơn hàng & trạng thái thanh toán
      */
     public function updateStatus(Request $request, Order $order)
     {
         $request->validate([
             'status'         => 'required|in:pending,shipping,completed,cancelled',
             'payment_status' => 'required|in:unpaid,paid,refunded',
         ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Nếu chuyển sang trạng thái đã hủy (cancelled) mà trước đó chưa hủy, hoàn lại kho
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
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
        }

        // Nếu từ đã hủy (cancelled) đổi lại thành trạng thái khác, trừ lại kho
        if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->variant_id) {
                    $variant = ProductVariant::find($item->variant_id);
                    if ($variant && $variant->stock >= $item->quantity) {
                        $variant->decrement('stock', $item->quantity);
                    }
                }
                if ($item->product_id) {
                    $product = Product::find($item->product_id);
                    if ($product && $product->stock >= $item->quantity) {
                        $product->decrement('stock', $item->quantity);
                    }
                }
            }
        }

        $order->update([
            'status'         => $newStatus,
            'payment_status' => $request->payment_status,
        ]);

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng ' . $order->order_code . ' thành công!');
    }

    /**
     * Xác nhận đơn hàng đã giao thành công (1 đơn hàng)
     */
    public function confirmDeliveredSingle(Order $order)
    {
        if ($order->status === 'cancelled') {
            return back()->with('error', 'Không thể xác nhận giao hàng cho đơn hàng đã bị hủy.');
        }

        $order->update([
            'status'         => 'completed',
            'payment_status' => 'paid',
        ]);

        return back()->with('success', "Đã xác nhận đơn hàng {$order->order_code} giao thành công (Đã hoàn thành)!");
    }

    /**
     * Xác nhận đã giao hàng cho nhiều đơn hàng cùng lúc (Hàng loạt qua checkbox)
     */
    public function confirmDeliveredBulk(Request $request)
    {
        $request->validate([
            'order_ids'   => 'required|array',
            'order_ids.*' => 'exists:orders,id',
        ], [
            'order_ids.required' => 'Vui lòng tích chọn ít nhất 1 đơn hàng để xác nhận đã giao.',
        ]);

        $updated = Order::whereIn('id', $request->order_ids)
            ->where('status', '!=', 'cancelled')
            ->update([
                'status'         => 'completed',
                'payment_status' => 'paid',
            ]);

        return back()->with('success', "Đã xác nhận đã giao thành công cho {$updated} đơn hàng!");
    }
}
