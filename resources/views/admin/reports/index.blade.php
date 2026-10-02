@extends('layouts.admin')

@section('title', 'Báo Cáo Doanh Thu - Thái Sơn Admin')
@section('page_title', 'Báo Cáo Doanh Thu')

@section('styles')
<style>
    /* KPI Card Animations & Hover */
    .kpi-card {
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
        border: 1px solid var(--border-color);
        background: #ffffff;
    }
    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(38, 24, 16, 0.12) !important;
    }
    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: currentColor;
    }
    .kpi-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        transition: all 0.3s ease;
    }
    .kpi-card:hover .kpi-icon-box {
        transform: scale(1.1) rotate(6deg);
    }
    .filter-pill {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 20px;
        border: 1px solid var(--border-color);
        background: #ffffff;
        color: #64748b;
        text-decoration: none;
        transition: all 0.2s;
    }
    .filter-pill:hover, .filter-pill.active {
        background: linear-gradient(135deg, #3d281d 0%, #54392b 100%);
        border-color: #54392b;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(61, 40, 29, 0.25);
    }
    .progress-thin {
        height: 6px;
        border-radius: 10px;
        background-color: #f1f5f9;
        overflow: hidden;
    }
    .counter-value {
        transition: all 0.5s ease-out;
    }
</style>
@endsection

@section('content')
<!-- Header Card & Navigation -->
<div class="admin-card mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h4 class="fw-bold mb-1"><i class="fa-solid fa-chart-line me-2 text-primary"></i>Thống Kê Doanh Thu & Bán Hàng</h4>
            <p class="text-muted mb-0 small">Báo cáo doanh thu chỉ tính trên các đơn hàng đã thanh toán và không bị hủy/hoàn trả.</p>
        </div>
        <nav class="nav nav-pills" aria-label="Báo cáo">
            <a class="nav-link active btn-sm" aria-current="page" href="{{ route('admin.reports.index', request()->query()) }}">
                <i class="fa-solid fa-table me-1"></i> Bảng số liệu
            </a>
            <a class="nav-link btn-sm" href="{{ route('admin.reports.charts', request()->query()) }}">
                <i class="fa-solid fa-chart-column me-1"></i> Biểu đồ trực quan
            </a>
        </nav>
    </div>
</div>

<!-- Bộ Lọc Nâng Cao (Advanced Filters) -->
<div class="admin-card mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pb-3 mb-3 border-bottom">
        <h6 class="fw-bold mb-0 text-dark">
            <i class="fa-solid fa-sliders me-1 text-primary"></i> Bộ Lọc Báo Cáo
        </h6>
        <!-- Quick Preset Pills -->
        <div class="d-flex flex-wrap gap-2">
            @php $curPreset = request('preset', (request()->hasAny(['date_from', 'date_to']) ? '' : 'all')); @endphp
            <a href="{{ route('admin.reports.index', array_merge(request()->except(['date_from', 'date_to', 'preset']), ['preset' => 'all'])) }}" class="filter-pill {{ $curPreset === 'all' ? 'active' : '' }}">Tất cả</a>
            <a href="{{ route('admin.reports.index', array_merge(request()->except(['date_from', 'date_to']), ['preset' => '7days'])) }}" class="filter-pill {{ $curPreset === '7days' ? 'active' : '' }}">7 ngày qua</a>
            <a href="{{ route('admin.reports.index', array_merge(request()->except(['date_from', 'date_to']), ['preset' => '30days'])) }}" class="filter-pill {{ $curPreset === '30days' ? 'active' : '' }}">30 ngày qua</a>
            <a href="{{ route('admin.reports.index', array_merge(request()->except(['date_from', 'date_to']), ['preset' => 'this_month'])) }}" class="filter-pill {{ $curPreset === 'this_month' ? 'active' : '' }}">Tháng này</a>
            <a href="{{ route('admin.reports.index', array_merge(request()->except(['date_from', 'date_to']), ['preset' => 'this_year'])) }}" class="filter-pill {{ $curPreset === 'this_year' ? 'active' : '' }}">Năm nay</a>
        </div>
    </div>

    <form action="{{ route('admin.reports.index') }}" method="GET">
        <input type="hidden" name="preset" value="{{ request('preset') }}">
        <div class="row g-3 align-items-end">
            <!-- Từ ngày -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-semibold">Từ ngày:</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm">
            </div>

            <!-- Đến ngày -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-semibold">Đến ngày:</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm">
            </div>

            <!-- Danh mục sản phẩm -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-semibold">Danh mục sản phẩm:</label>
                <select name="category_id" class="form-select form-select-sm">
                    <option value="all">Tất cả danh mục</option>
                    @foreach($categoriesList as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Phương thức thanh toán -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-semibold">Phương thức thanh toán:</label>
                <select name="payment_method" class="form-select form-select-sm">
                    <option value="all">Tất cả phương thức</option>
                    <option value="momo" @selected(request('payment_method') === 'momo')>Ví MoMo</option>
                    <option value="banking" @selected(request('payment_method') === 'banking')>Chuyển khoản VietQR</option>
                    <option value="cod" @selected(request('payment_method') === 'cod')>Thanh toán COD</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fa-solid fa-rotate-left me-1"></i> Đặt lại
                </a>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-filter me-1"></i> Áp dụng bộ lọc
                </button>
            </div>
        </div>
    </form>
</div>

<!-- 3 KPI Cards Thống kê tổng quan -->
<div class="row g-4 mb-4">
    <!-- Tổng đơn hàng -->
    <div class="col-md-4">
        <div class="kpi-card shadow-sm p-4 text-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-bold text-uppercase letter-spacing">Tổng Đơn Hàng</span>
                    <h2 class="fw-bold mt-2 mb-0 text-dark counter-value" data-target="{{ $totalOrders }}">0</h2>
                    <div class="small text-success mt-1"><i class="fa-solid fa-arrow-trend-up me-1"></i>Đơn đã thanh toán</div>
                </div>
                <div class="kpi-icon-box bg-primary-subtle text-primary">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tổng khách hàng -->
    <div class="col-md-4">
        <div class="kpi-card shadow-sm p-4" style="color: #0284c7;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-bold text-uppercase letter-spacing">Tổng Khách Hàng</span>
                    <h2 class="fw-bold mt-2 mb-0 text-dark counter-value" data-target="{{ $totalCustomers }}">0</h2>
                    <div class="small text-info mt-1"><i class="fa-solid fa-users me-1"></i>Khách hàng phát sinh đơn</div>
                </div>
                <div class="kpi-icon-box bg-info-subtle text-info">
                    <i class="fa-solid fa-user-group"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tổng doanh thu -->
    <div class="col-md-4">
        <div class="kpi-card shadow-sm p-4 text-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-bold text-uppercase letter-spacing">Tổng Doanh Thu</span>
                    <h2 class="fw-bold mt-2 mb-0 text-success counter-currency" data-target="{{ (int)$totalRevenue }}">0 đ</h2>
                    <div class="small text-muted mt-1"><i class="fa-solid fa-shield-halved text-success me-1"></i>Đã thu thực tế</div>
                </div>
                <div class="kpi-icon-box bg-success-subtle text-success">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Doanh thu theo danh mục sản phẩm (Có thanh tỷ trọng %) -->
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0"><i class="fa-solid fa-layer-group text-primary me-2"></i>Doanh thu theo danh mục</h5>
            <small class="text-muted">Tính theo giá trị sản phẩm trong các đơn hàng thực tế.</small>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-custom-head">
                <tr>
                    <th>Danh mục</th>
                    <th class="text-end" style="width: 140px;">Số lượng bán</th>
                    <th class="text-end" style="width: 180px;">Doanh thu</th>
                    <th style="width: 220px;">Tỷ trọng doanh thu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categoryRevenue as $revenue)
                @php
                    $percent = ($totalRevenue > 0) ? round(($revenue->total_revenue / $totalRevenue) * 100, 1) : 0;
                @endphp
                <tr>
                    <td class="fw-semibold">
                        <i class="fa-solid fa-cube text-muted me-1 small"></i>
                        {{ $revenue->category_name ?? ('Danh mục #'.$revenue->category_id) }}
                    </td>
                    <td class="text-end fw-semibold">{{ number_format($revenue->total_qty) }}</td>
                    <td class="text-end fw-bold text-success">{{ number_format($revenue->total_revenue, 0, ',', '.') }} đ</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress-thin flex-grow-1">
                                <div class="bg-success h-100" style="width: {{ min(100, $percent) }}%;"></div>
                            </div>
                            <span class="small fw-bold text-muted" style="min-width: 45px;">{{ $percent }}%</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">Chưa có dữ liệu doanh thu trong khoảng thời gian đã chọn.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Doanh thu theo Ngày / Tháng / Năm -->
@foreach([
    ['Doanh thu chi tiết theo ngày', 'Ngày', 'date', $revenueByDate, 'd/m/Y', 'fa-calendar-day text-primary'],
    ['Doanh thu chi tiết theo tháng', 'Tháng', 'month', $revenueByMonth, 'm/Y', 'fa-calendar-week text-info'],
    ['Doanh thu chi tiết theo năm', 'Năm', 'year', $revenueByYear, null, 'fa-calendar text-warning'],
] as [$title, $label, $field, $rows, $format, $iconClass])
<div class="admin-card mb-4">
    <h5 class="fw-bold mb-3"><i class="fa-solid {{ $iconClass }} me-2"></i>{{ $title }}</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-custom-head">
                <tr>
                    <th>{{ $label }}</th>
                    <th class="text-end">Số đơn đã thanh toán</th>
                    <th class="text-end">Doanh thu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $revenue)
                <tr>
                    <td class="fw-semibold">{{ $format ? \Carbon\Carbon::parse($revenue->{$field}.($field === 'month' ? '-01' : ''))->format($format) : $revenue->{$field} }}</td>
                    <td class="text-end">{{ number_format($revenue->order_count) }}</td>
                    <td class="text-end fw-bold text-success">{{ number_format($revenue->total_revenue, 0, ',', '.') }} đ</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-4">Chưa có dữ liệu trong kỳ báo cáo này.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script>
// Animated Counter Effect
document.addEventListener('DOMContentLoaded', function() {
    function animateCounters() {
        document.querySelectorAll('.counter-value').forEach(el => {
            const target = +el.getAttribute('data-target');
            let current = 0;
            const increment = Math.max(1, Math.ceil(target / 40));
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    el.textContent = target.toLocaleString('vi-VN');
                    clearInterval(timer);
                } else {
                    el.textContent = current.toLocaleString('vi-VN');
                }
            }, 25);
        });

        document.querySelectorAll('.counter-currency').forEach(el => {
            const target = +el.getAttribute('data-target');
            let current = 0;
            const increment = Math.max(1000, Math.ceil(target / 40));
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    el.textContent = target.toLocaleString('vi-VN') + ' đ';
                    clearInterval(timer);
                } else {
                    el.textContent = current.toLocaleString('vi-VN') + ' đ';
                }
            }, 25);
        });
    }

    animateCounters();
});
</script>
@endsection
