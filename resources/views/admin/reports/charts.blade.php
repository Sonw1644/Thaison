@extends('layouts.admin')

@section('title', 'Biểu Đồ Báo Cáo Doanh Thu - Thái Sơn Admin')
@section('page_title', 'Biểu Đồ Báo Cáo Doanh Thu')

@section('styles')
<style>
    .chart-card {
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid var(--border-color);
        background: #ffffff;
    }
    .chart-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(38, 24, 16, 0.1) !important;
    }
    .chart-wrap {
        position: relative;
        min-height: 320px;
        height: 320px;
        width: 100%;
    }
    .chart-wrap canvas {
        width: 100% !important;
        height: 100% !important;
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
</style>
@endsection

@section('content')
<!-- Header Card & Navigation -->
<div class="admin-card mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h4 class="fw-bold mb-1"><i class="fa-solid fa-chart-column me-2 text-primary"></i>Trực Quan Hóa Doanh Thu & Biểu Đồ</h4>
            <p class="text-muted mb-0 small">Phân tích xu hướng tăng trưởng, tỷ trọng danh mục và phương thức thanh toán.</p>
        </div>
        <nav class="nav nav-pills" aria-label="Báo cáo">
            <a class="nav-link btn-sm" href="{{ route('admin.reports.index', request()->query()) }}">
                <i class="fa-solid fa-table me-1"></i> Bảng số liệu
            </a>
            <a class="nav-link active btn-sm" aria-current="page" href="{{ route('admin.reports.charts', request()->query()) }}">
                <i class="fa-solid fa-chart-column me-1"></i> Biểu đồ trực quan
            </a>
        </nav>
    </div>
</div>

<!-- Bộ Lọc Nâng Cao (Advanced Filters) -->
<div class="admin-card mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pb-3 mb-3 border-bottom">
        <h6 class="fw-bold mb-0 text-dark">
            <i class="fa-solid fa-sliders me-1 text-primary"></i> Bộ Lọc Biểu Đồ
        </h6>
        <!-- Quick Preset Pills -->
        <div class="d-flex flex-wrap gap-2">
            @php $curPreset = request('preset', (request()->hasAny(['date_from', 'date_to']) ? '' : 'all')); @endphp
            <a href="{{ route('admin.reports.charts', array_merge(request()->except(['date_from', 'date_to', 'preset']), ['preset' => 'all'])) }}" class="filter-pill {{ $curPreset === 'all' ? 'active' : '' }}">Tất cả</a>
            <a href="{{ route('admin.reports.charts', array_merge(request()->except(['date_from', 'date_to']), ['preset' => '7days'])) }}" class="filter-pill {{ $curPreset === '7days' ? 'active' : '' }}">7 ngày qua</a>
            <a href="{{ route('admin.reports.charts', array_merge(request()->except(['date_from', 'date_to']), ['preset' => '30days'])) }}" class="filter-pill {{ $curPreset === '30days' ? 'active' : '' }}">30 ngày qua</a>
            <a href="{{ route('admin.reports.charts', array_merge(request()->except(['date_from', 'date_to']), ['preset' => 'this_month'])) }}" class="filter-pill {{ $curPreset === 'this_month' ? 'active' : '' }}">Tháng này</a>
            <a href="{{ route('admin.reports.charts', array_merge(request()->except(['date_from', 'date_to']), ['preset' => 'this_year'])) }}" class="filter-pill {{ $curPreset === 'this_year' ? 'active' : '' }}">Năm nay</a>
        </div>
    </div>

    <form action="{{ route('admin.reports.charts') }}" method="GET">
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
                <a href="{{ route('admin.reports.charts') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fa-solid fa-rotate-left me-1"></i> Đặt lại
                </a>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-chart-line me-1"></i> Cập nhật biểu đồ
                </button>
            </div>
        </div>
    </form>
</div>

<div id="report-chart-error" class="alert alert-warning d-none" role="alert">
    Không tải được thư viện biểu đồ. Bạn có thể xem số liệu tại trang <a href="{{ route('admin.reports.index') }}" class="alert-link">Bảng số liệu</a>.
</div>

<div class="row g-4">
    <!-- Doanh thu theo danh mục -->
    <div class="col-lg-6">
        <div class="admin-card chart-card h-100">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-chart-pie me-2 text-warning"></i> Doanh thu theo danh mục</h5>
            <div class="chart-wrap">
                <canvas id="categoryRevenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Doanh thu theo ngày -->
    <div class="col-lg-6">
        <div class="admin-card chart-card h-100">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-chart-line me-2 text-primary"></i> Xu hướng doanh thu theo ngày</h5>
            <div class="chart-wrap">
                <canvas id="revenueByDateChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Doanh thu theo tháng -->
    <div class="col-lg-6">
        <div class="admin-card chart-card h-100">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-chart-simple me-2 text-success"></i> Doanh thu theo tháng (12 tháng gần nhất)</h5>
            <div class="chart-wrap">
                <canvas id="revenueByMonthChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Doanh thu theo năm -->
    <div class="col-lg-6">
        <div class="admin-card chart-card h-100">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-signal me-2 text-info"></i> Doanh thu theo từng năm</h5>
            <div class="chart-wrap">
                <canvas id="revenueByYearChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Doanh thu theo phương thức thanh toán -->
    <div class="col-lg-12">
        <div class="admin-card chart-card">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-credit-card me-2 text-danger"></i> Tỷ trọng doanh thu theo Phương thức thanh toán</h5>
            <div class="chart-wrap" style="max-height: 360px;">
                <canvas id="revenueByPaymentMethodChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div id="report-chart-data" hidden data-chart-data="{{ json_encode([
    'catLabels' => $catLabels ?? [],
    'catRevenue' => $catRevenue ?? [],
    'revDateLabels' => $revDateLabels ?? [],
    'revDateData' => $revDateData ?? [],
    'revMonthLabels' => $revMonthLabels ?? [],
    'revMonthData' => $revMonthData ?? [],
    'revYearLabels' => $revYearLabels ?? [],
    'revYearData' => $revYearData ?? [],
    'paymentMethodLabels' => $paymentMethodLabels ?? [],
    'paymentMethodRevenue' => $paymentMethodRevenue ?? [],
]) }}"></div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.addEventListener('DOMContentLoaded', () => {
    if (typeof Chart === 'undefined') {
        document.getElementById('report-chart-error').classList.remove('d-none');
        return;
    }

    const reportData = JSON.parse(document.getElementById('report-chart-data').dataset.chartData);

    const catLabels = reportData.catLabels;
    const catRevenue = reportData.catRevenue.map(Number);

    const revDateLabels = reportData.revDateLabels;
    const revDateData = reportData.revDateData.map(Number);

    const revMonthLabels = reportData.revMonthLabels;
    const revMonthData = reportData.revMonthData.map(Number);

    const revYearLabels = reportData.revYearLabels;
    const revYearData = reportData.revYearData.map(Number);

    const payLabels = reportData.paymentMethodLabels;
    const payRevenue = reportData.paymentMethodRevenue.map(Number);

    // Global Chart Defaults
    Chart.defaults.font.family = "'Be Vietnam Pro', sans-serif";
    Chart.defaults.color = '#64748b';

    const mk = (el, type, labels, data, label, bgColor, borderColor) => new Chart(el, {
        type,
        data: {
            labels,
            datasets: [{
                label,
                data,
                fill: type === 'line',
                backgroundColor: bgColor || 'rgba(108, 78, 49, 0.25)',
                borderColor: borderColor || '#6c4e31',
                borderWidth: 2.5,
                borderRadius: type === 'bar' ? 8 : 0,
                tension: 0.35,
                pointBackgroundColor: borderColor || '#6c4e31',
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(226, 232, 240, 0.6)' },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                },
                x: {
                    grid: { display: false }
                }
            },
            plugins: {
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (context.parsed.y !== null) {
                                label += context.parsed.y.toLocaleString('vi-VN') + ' VNĐ';
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });

    if (document.getElementById('categoryRevenueChart')) {
        mk(document.getElementById('categoryRevenueChart'), 'bar', catLabels, catRevenue, 'Doanh thu (VNĐ)', 'rgba(217, 119, 6, 0.45)', '#d97706');
    }
    if (document.getElementById('revenueByDateChart')) {
        mk(document.getElementById('revenueByDateChart'), 'line', revDateLabels, revDateData, 'Doanh thu (VNĐ)', 'rgba(2, 132, 199, 0.15)', '#0284c7');
    }
    if (document.getElementById('revenueByMonthChart')) {
        mk(document.getElementById('revenueByMonthChart'), 'bar', revMonthLabels, revMonthData, 'Doanh thu (VNĐ)', 'rgba(22, 163, 74, 0.45)', '#16a34a');
    }
    if (document.getElementById('revenueByYearChart')) {
        mk(document.getElementById('revenueByYearChart'), 'bar', revYearLabels, revYearData, 'Doanh thu (VNĐ)', 'rgba(108, 78, 49, 0.45)', '#6c4e31');
    }
    if (document.getElementById('revenueByPaymentMethodChart')) {
        new Chart(document.getElementById('revenueByPaymentMethodChart'), {
            type: 'doughnut',
            data: {
                labels: payLabels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: payRevenue,
                    backgroundColor: ['#a855f7', '#0284c7', '#64748b'],
                    borderWidth: 2,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 16 }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                let val = context.parsed || 0;
                                return ` ${context.label}: ${val.toLocaleString('vi-VN')} VNĐ`;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection
