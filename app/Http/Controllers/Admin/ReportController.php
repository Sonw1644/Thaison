<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Query các đơn hàng đã thanh toán có áp dụng bộ lọc ngày, danh mục, phương thức
     */
    private function paidOrders(Request $request): Builder
    {
        $paymentStatus = DB::table('payment_transactions')->select('status')
            ->whereColumn('order_id', 'orders.id')
            ->orderByRaw("CASE WHEN status IN ('paid', 'refund_pending', 'refunded') THEN 0 ELSE 1 END")
            ->orderByDesc('id')->limit(1);

        $query = Order::query()
            ->where('orders.status', '!=', 'cancelled')
            ->whereNotIn('orders.shipping_status', ['cancelled', 'return', 'returned'])
            ->where(function (Builder $q) use ($paymentStatus) {
                $q->where($paymentStatus, 'paid')
                    ->orWhere(function (Builder $legacy) {
                        $legacy->whereDoesntHave('paymentTransactions')
                            ->whereIn('orders.status', ['paid', 'cod_paid', 'paid_momo', 'completed', 'shipping']);
                    });
            });

        // Xử lý bộ lọc nhanh Preset nếu có
        if ($request->filled('preset') && !$request->filled('date_from')) {
            match ($request->preset) {
                'today'      => $query->whereDate('orders.created_at', Carbon::today()),
                'yesterday'  => $query->whereDate('orders.created_at', Carbon::yesterday()),
                '7days'      => $query->where('orders.created_at', '>=', Carbon::now()->subDays(7)->startOfDay()),
                '30days'     => $query->where('orders.created_at', '>=', Carbon::now()->subDays(30)->startOfDay()),
                'this_month' => $query->where('orders.created_at', '>=', Carbon::now()->startOfMonth()),
                'last_month' => $query->whereBetween('orders.created_at', [
                    Carbon::now()->subMonth()->startOfMonth(),
                    Carbon::now()->subMonth()->endOfMonth()
                ]),
                'this_year'  => $query->where('orders.created_at', '>=', Carbon::now()->startOfYear()),
                default      => null,
            };
        } else {
            // Lọc theo ngày bắt đầu
            if ($request->filled('date_from')) {
                $query->where('orders.created_at', '>=', Carbon::parse($request->date_from)->startOfDay());
            }
            // Lọc theo ngày kết thúc
            if ($request->filled('date_to')) {
                $query->where('orders.created_at', '<=', Carbon::parse($request->date_to)->endOfDay());
            }
        }

        // Lọc theo phương thức thanh toán
        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->where('orders.payment_method', $request->payment_method);
        }

        // Lọc theo danh mục sản phẩm
        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->whereHas('items.product', function ($pQuery) use ($request) {
                $pQuery->where('category_id', $request->category_id);
            });
        }

        return $query;
    }

    private function categoryRevenue(Request $request): Collection
    {
        $orderIds = $this->paidOrders($request)->select('orders.id');

        $query = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->whereIn('order_items.order_id', $orderIds);

        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('products.category_id', $request->category_id);
        }

        return $query->select('products.category_id', 'categories.name as category_name')
            ->selectRaw('SUM(order_items.price * order_items.quantity) as total_revenue, SUM(order_items.quantity) as total_qty')
            ->groupBy('products.category_id', 'categories.name')
            ->orderByDesc('total_revenue')->get();
    }

    private function dailyRevenue(Request $request): Collection
    {
        return $this->paidOrders($request)
            ->selectRaw('DATE(orders.created_at) as date, SUM(total_amount) as total_revenue, COUNT(*) as order_count')
            ->groupByRaw('DATE(orders.created_at)')->orderBy('date')->get();
    }

    private function periodRevenue(Collection $days, string $period): Collection
    {
        return $days->groupBy(fn ($day) => substr($day->date, 0, $period === 'month' ? 7 : 4))
            ->map(fn (Collection $rows, $key) => (object) [
                $period => (string) $key,
                'total_revenue' => $rows->sum('total_revenue'),
                'order_count'   => $rows->sum('order_count'),
            ])->values();
    }

    public function index(Request $request)
    {
        $categoriesList = Category::orderBy('name')->get();
        $categoryRevenue = $this->categoryRevenue($request);
        
        $paidQuery = $this->paidOrders($request);
        $totalOrders = $paidQuery->count();
        $totalCustomers = (clone $paidQuery)->whereNotNull('user_id')->distinct('user_id')->count('user_id');
        if ($totalCustomers === 0) {
            $totalCustomers = DB::table('users')->where('role', '!=', 'admin')->count();
        }

        $revenueByDate = $this->dailyRevenue($request);
        $revenueByMonth = $this->periodRevenue($revenueByDate, 'month');
        $revenueByYear = $this->periodRevenue($revenueByDate, 'year');
        $totalRevenue = $revenueByDate->sum('total_revenue');

        return view('admin.reports.index', compact(
            'categoriesList', 'categoryRevenue', 'totalOrders', 'totalCustomers', 'totalRevenue',
            'revenueByDate', 'revenueByMonth', 'revenueByYear'
        ));
    }

    public function charts(Request $request)
    {
        $categoriesList = Category::orderBy('name')->get();
        $categories = $this->categoryRevenue($request);
        $catLabels = $categories->map(fn ($row) => $row->category_name ?? 'Danh mục #'.$row->category_id)->all();
        $catRevenue = $categories->pluck('total_revenue')->map(fn ($value) => (float) $value)->all();

        $daily = $this->dailyRevenue($request);
        $byDate = $daily->keyBy('date');
        $byMonth = $this->periodRevenue($daily, 'month')->keyBy('month');
        $byYear = $this->periodRevenue($daily, 'year');

        // Khoảng ngày mặc định 30 ngày hoặc theo date_from -> date_to
        $startDay = $request->filled('date_from') 
            ? Carbon::parse($request->date_from)->startOfDay() 
            : Carbon::now()->startOfDay()->subDays(29);
        $endDay = $request->filled('date_to')
            ? Carbon::parse($request->date_to)->startOfDay()
            : Carbon::now()->startOfDay();

        $daysDiff = min(max(1, $startDay->diffInDays($endDay) + 1), 60);

        $revDateLabels = [];
        $revDateData = [];

        for ($i = 0; $i < $daysDiff; $i++) {
            $curDate = $startDay->copy()->addDays($i)->toDateString();
            $revDateLabels[] = Carbon::parse($curDate)->format('d/m');
            $revDateData[] = (float) ($byDate->get($curDate)?->total_revenue ?? 0);
        }

        $startMonth = Carbon::now()->startOfMonth()->subMonths(11);
        $revMonthLabels = [];
        $revMonthData = [];
        for ($i = 0; $i < 12; $i++) {
            $month = $startMonth->copy()->addMonths($i);
            $revMonthLabels[] = $month->format('m/Y');
            $revMonthData[] = (float) ($byMonth->get($month->format('Y-m'))?->total_revenue ?? 0);
        }

        $revYearLabels = $byYear->pluck('year')->all();
        $revYearData = $byYear->pluck('total_revenue')->map(fn ($value) => (float) $value)->all();

        // Thống kê theo phương thức thanh toán
        $paidOrderIds = $this->paidOrders($request)->select('orders.id');
        $methodStats = Order::whereIn('id', $paidOrderIds)
            ->select('payment_method')
            ->selectRaw('SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        $paymentMethodLabels = ['MoMo', 'VietQR (Banking)', 'COD'];
        $paymentMethodRevenue = [
            (float) ($methodStats->get('momo', 0)),
            (float) ($methodStats->get('banking', 0)),
            (float) ($methodStats->get('cod', 0)),
        ];

        return view('admin.reports.charts', compact(
            'categoriesList', 'catLabels', 'catRevenue', 'revDateLabels', 'revDateData',
            'revMonthLabels', 'revMonthData', 'revYearLabels', 'revYearData',
            'paymentMethodLabels', 'paymentMethodRevenue'
        ));
    }
}
