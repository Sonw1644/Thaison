<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;

class AdminController extends Controller
{
    public function dashboard()
    {
        $productCount = Product::count();
        $categoryCount = Category::count();
        $userCount = User::count();
        $totalStock = Product::sum('stock');
        $lowStockCount = Product::where('stock', '<=', 50)->where('stock', '>', 0)->count();
        $outOfStockCount = Product::where('stock', '<=', 0)->count();
        $recentProducts = Product::with(['category', 'variants'])->latest()->take(6)->get();
        $recentCategories = Category::withCount('products')->latest()->take(5)->get();

        // Thống kê Đơn hàng & Doanh thu
        $orderCount = Order::count();
        $pendingOrderCount = Order::where('status', 'pending')->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $recentOrders = Order::with('items')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'productCount',
            'categoryCount',
            'userCount',
            'totalStock',
            'lowStockCount',
            'outOfStockCount',
            'recentProducts',
            'recentCategories',
            'orderCount',
            'pendingOrderCount',
            'totalRevenue',
            'recentOrders'
        ));
    }
}
