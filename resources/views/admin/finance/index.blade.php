@extends('layouts.admin')

@section('title', 'Thống Kê Tài Chính - Quản Trị')
@section('page_title', 'Thống kê tài chính')

@section('styles')
<style>
    /* Áp dụng font chữ Inter */
    .finance-wrapper,
    .finance-wrapper * {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
    }

    /* Tabs phong cách Nâu Be */
    .finance-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 22px;
    }
    .finance-tab-item {
        font-size: 0.88rem;
        padding: 8px 18px;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: inline-block;
        font-weight: 600;
    }
    .finance-tab-item.active {
        background: linear-gradient(135deg, #261810 0%, #3d281d 100%) !important;
        color: #ffffff !important;
        border: 1px solid #3d281d;
        box-shadow: 0 4px 10px rgba(38, 24, 16, 0.2);
    }
    .finance-tab-item.inactive {
        color: #8b5e3c !important;
        background: #fbf8f5 !important;
        border: 1px solid #e9e0d6 !important;
    }
    .finance-tab-item.inactive:hover {
        background: #f3eae0 !important;
        color: #54392b !important;
    }

    /* Khung card & bộ lọc */
    .finance-card {
        background: #ffffff;
        border: 1px solid #ebd9c8;
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(38, 24, 16, 0.04);
    }
    .finance-filter-label {
        font-size: 0.82rem;
        color: #786558;
        margin-bottom: 6px;
        font-weight: 600;
        display: block;
    }
    .finance-input {
        border: 1px solid #e2d9ce !important;
        border-radius: 8px !important;
        font-size: 0.86rem !important;
        height: 38px !important;
        padding: 6px 12px !important;
        color: #261810 !important;
        background-color: #ffffff !important;
    }
    .finance-input:focus {
        border-color: #c48b52 !important;
        box-shadow: 0 0 0 0.25rem rgba(196, 139, 82, 0.2) !important;
    }

    /* Nút bấm Nâu Be */
    .btn-finance-primary {
        background: linear-gradient(135deg, #261810 0%, #3d281d 100%) !important;
        border: 1px solid #6c4e31 !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        font-size: 0.86rem !important;
        height: 38px !important;
        padding: 0 20px !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 6px rgba(38, 24, 16, 0.15) !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.2s ease !important;
    }
    .btn-finance-primary:hover {
        background: linear-gradient(135deg, #3d281d 0%, #503527 100%) !important;
        border-color: #d4a373 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(38, 24, 16, 0.25) !important;
    }
    .btn-finance-outline {
        background: #ffffff !important;
        border: 1px solid #dcd1c4 !important;
        color: #54392b !important;
        font-weight: 600 !important;
        font-size: 0.86rem !important;
        height: 38px !important;
        padding: 0 18px !important;
        border-radius: 8px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-decoration: none !important;
        transition: all 0.2s ease !important;
    }
    .btn-finance-outline:hover {
        background: #f7f3ee !important;
        border-color: #bfa892 !important;
        color: #261810 !important;
    }

    /* 8 Thẻ thống kê tài chính Nâu Be */
    .stat-metric-card {
        background-color: #ffffff;
        border: 1px solid #ebd9c8;
        border-radius: 10px;
        padding: 18px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 1px 4px rgba(38, 24, 16, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(38, 24, 16, 0.08);
    }
    .stat-metric-card.main-stat {
        background: linear-gradient(135deg, #fdfbf8 0%, #f6eee5 100%);
        border: 1px solid #dfcfc0;
    }
    .stat-metric-title {
        font-size: 0.82rem;
        color: #786558;
        font-weight: 600;
        margin-bottom: 8px;
    }
    .stat-metric-amount {
        font-family: 'Outfit', sans-serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: #261810;
        margin-bottom: 4px;
        line-height: 1.2;
    }
    .stat-metric-card.main-stat .stat-metric-amount {
        color: #8b5e3c;
    }
    .stat-metric-sub {
        font-size: 0.78rem;
        color: #8b7970;
    }

    /* Bảng phương thức */
    .finance-method-table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .finance-method-table thead th {
        background-color: #fbf9f6 !important;
        color: #3d281d !important;
        font-size: 0.85rem !important;
        font-weight: 700 !important;
        padding: 12px 16px !important;
        border-bottom: 1px solid #ebd9c8 !important;
    }
    .finance-method-table tbody td {
        font-size: 0.85rem !important;
        color: #261810 !important;
        padding: 12px 16px !important;
        border-bottom: 1px solid #f2ece4 !important;
    }
    .finance-method-table tbody tr:hover td {
        background-color: rgba(229, 178, 121, 0.08) !important;
    }

    .finance-badge-method {
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.78rem;
    }
    .badge-method-cod {
        background-color: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }
    .badge-method-momo {
        background-color: #fdf2f8;
        color: #be185d;
        border: 1px solid #fbcfe8;
    }
    .badge-method-unknown {
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0 finance-wrapper">
    <!-- Breadcrumb & Header Title -->
    <div class="mb-3">
        <div class="text-secondary small mb-1" style="font-size: 0.8rem;">
            Quản trị / Thống kê tài chính
        </div>
        <h4 class="fw-bold mb-1" style="color: #261810; font-size: 1.35rem;">Thống kê tài chính</h4>
        <div class="text-secondary small">Tổng hợp giá trị thanh toán theo trạng thái và phương thức</div>
    </div>

    <!-- Tabs chuyển đổi giữa 2 trang -->
    <div class="finance-tabs">
        <a href="{{ route('admin.finance.index') }}" class="finance-tab-item active">
            Thống kê chỉ số
        </a>
        <a href="{{ route('admin.finance.transactions') }}" class="finance-tab-item inactive">
            Giao dịch thanh toán
        </a>
    </div>

    <!-- Khối bộ lọc tìm kiếm -->
    <div class="finance-card">
        <form action="{{ route('admin.finance.index') }}" method="GET">
            <!-- Dòng 1: Tìm đơn hàng, Từ ngày, Đến ngày -->
            <div class="row g-3 mb-3">
                <div class="col-md-6 col-lg-5">
                    <label class="finance-filter-label">Tìm đơn hàng</label>
                    <input type="text" name="search" class="form-control finance-input" placeholder="Mã đơn, tên hoặc số điện thoại" value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="col-md-3 col-lg-3">
                    <label class="finance-filter-label">Từ ngày tạo đơn</label>
                    <input type="date" name="date_from" class="form-control finance-input" value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div class="col-md-3 col-lg-4">
                    <label class="finance-filter-label">Đến ngày tạo đơn</label>
                    <input type="date" name="date_to" class="form-control finance-input" value="{{ $filters['date_to'] ?? '' }}">
                </div>
            </div>

            <!-- Dòng 2: Số tiền từ, Số tiền đến, Phương thức, Trạng thái thanh toán -->
            <div class="row g-3 mb-3">
                <div class="col-md-3 col-lg-3">
                    <label class="finance-filter-label">Số tiền từ (đ)</label>
                    <input type="number" step="any" min="0" name="min_amount" class="form-control finance-input" placeholder="Không giới hạn" value="{{ $filters['min_amount'] ?? '' }}">
                </div>
                <div class="col-md-3 col-lg-3">
                    <label class="finance-filter-label">Số tiền đến (đ)</label>
                    <input type="number" step="any" min="0" name="max_amount" class="form-control finance-input" placeholder="Không giới hạn" value="{{ $filters['max_amount'] ?? '' }}">
                </div>
                <div class="col-md-3 col-lg-3">
                    <label class="finance-filter-label">Phương thức</label>
                    <select name="gateway" class="form-select finance-input">
                        <option value="">Tất cả</option>
                        @foreach($methods as $k => $m)
                            <option value="{{ $k }}" {{ ($filters['gateway'] ?? '') === $k ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-lg-3">
                    <label class="finance-filter-label">Trạng thái thanh toán</label>
                    <select name="payment_status" class="form-select finance-input">
                        <option value="">Tất cả</option>
                        @foreach($statuses as $k => $s)
                            <option value="{{ $k }}" {{ ($filters['payment_status'] ?? '') === $k ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Nút thao tác bộ lọc -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-finance-primary">Áp dụng bộ lọc</button>
                <a href="{{ route('admin.finance.index') }}" class="btn btn-finance-outline">Xóa bộ lọc</a>
            </div>
        </form>
    </div>

    <!-- Thông báo kết quả lọc -->
    <div class="text-secondary small mb-3">
        Có <strong>{{ number_format($summary->order_count ?? 0) }}</strong> đơn phù hợp. Số tiền bao gồm phí vận chuyển; thống kê theo ngày tạo đơn trên toàn bộ kết quả lọc.
    </div>

    <!-- 8 Thẻ Thống Kê Chỉ Số (Grid 4x2) -->
    <div class="row g-3 mb-4">
        <!-- 1. Tổng giá trị đơn hàng -->
        <div class="col-sm-6 col-lg-3">
            <div class="stat-metric-card main-stat">
                <div class="stat-metric-title">Tổng giá trị đơn hàng</div>
                <div class="stat-metric-amount">{{ number_format($summary->total_amount ?? 0, 0, ',', '.') }} đ</div>
                <div class="stat-metric-sub">{{ number_format($summary->order_count ?? 0) }} đơn, bao gồm đơn đã hủy</div>
            </div>
        </div>

        <!-- 2. Chờ thanh toán -->
        <div class="col-sm-6 col-lg-3">
            <div class="stat-metric-card">
                <div class="stat-metric-title">Chờ thanh toán</div>
                <div class="stat-metric-amount">{{ number_format($statusTotals['pending']->total_amount ?? 0, 0, ',', '.') }} đ</div>
                <div class="stat-metric-sub">{{ number_format($statusTotals['pending']->order_count ?? 0) }} đơn</div>
            </div>
        </div>

        <!-- 3. Đang chờ MoMo -->
        <div class="col-sm-6 col-lg-3">
            <div class="stat-metric-card">
                <div class="stat-metric-title">Đang chờ MoMo</div>
                <div class="stat-metric-amount">{{ number_format($statusTotals['initiated']->total_amount ?? 0, 0, ',', '.') }} đ</div>
                <div class="stat-metric-sub">{{ number_format($statusTotals['initiated']->order_count ?? 0) }} đơn</div>
            </div>
        </div>

        <!-- 4. Đã thanh toán -->
        <div class="col-sm-6 col-lg-3">
            <div class="stat-metric-card">
                <div class="stat-metric-title">Đã thanh toán</div>
                <div class="stat-metric-amount text-success">{{ number_format($statusTotals['paid']->total_amount ?? 0, 0, ',', '.') }} đ</div>
                <div class="stat-metric-sub">{{ number_format($statusTotals['paid']->order_count ?? 0) }} đơn</div>
            </div>
        </div>

        <!-- 5. Thanh toán thất bại -->
        <div class="col-sm-6 col-lg-3">
            <div class="stat-metric-card">
                <div class="stat-metric-title">Thanh toán thất bại</div>
                <div class="stat-metric-amount text-danger">{{ number_format($statusTotals['failed']->total_amount ?? 0, 0, ',', '.') }} đ</div>
                <div class="stat-metric-sub">{{ number_format($statusTotals['failed']->order_count ?? 0) }} đơn</div>
            </div>
        </div>

        <!-- 6. Đã hủy -->
        <div class="col-sm-6 col-lg-3">
            <div class="stat-metric-card">
                <div class="stat-metric-title">Đã hủy</div>
                <div class="stat-metric-amount text-muted">{{ number_format($statusTotals['cancelled']->total_amount ?? 0, 0, ',', '.') }} đ</div>
                <div class="stat-metric-sub">{{ number_format($statusTotals['cancelled']->order_count ?? 0) }} đơn</div>
            </div>
        </div>

        <!-- 7. Chờ hoàn tiền -->
        <div class="col-sm-6 col-lg-3">
            <div class="stat-metric-card">
                <div class="stat-metric-title">Chờ hoàn tiền</div>
                <div class="stat-metric-amount text-warning">{{ number_format($statusTotals['refund_pending']->total_amount ?? 0, 0, ',', '.') }} đ</div>
                <div class="stat-metric-sub">{{ number_format($statusTotals['refund_pending']->order_count ?? 0) }} đơn</div>
            </div>
        </div>

        <!-- 8. Đã hoàn tiền -->
        <div class="col-sm-6 col-lg-3">
            <div class="stat-metric-card">
                <div class="stat-metric-title">Đã hoàn tiền</div>
                <div class="stat-metric-amount text-secondary">{{ number_format($statusTotals['refunded']->total_amount ?? 0, 0, ',', '.') }} đ</div>
                <div class="stat-metric-sub">{{ number_format($statusTotals['refunded']->order_count ?? 0) }} đơn</div>
            </div>
        </div>
    </div>

    <!-- Bảng Thống Kê Theo Phương Thức -->
    <div class="finance-card px-0 py-3">
        <div class="px-3 mb-2">
            <h5 class="fw-bold mb-0" style="color: #261810; font-size: 1rem;">Thống kê theo phương thức</h5>
        </div>
        <div class="table-responsive">
            <table class="table finance-method-table align-middle">
                <thead>
                    <tr>
                        <th style="width: 25%;">Phương thức</th>
                        <th style="width: 25%;">Số đơn</th>
                        <th style="width: 25%;">Tổng giá trị</th>
                        <th style="width: 25%;">Đã thanh toán</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($methods as $key => $methodName)
                        @php
                            $row = $methodTotals[$key] ?? null;
                            $orderCount = $row->order_count ?? 0;
                            $totalVal = $row->total_amount ?? 0;
                            $paidVal = $row->paid_amount ?? 0;
                        @endphp
                        <tr>
                            <td>
                                @if($key === 'cod')
                                    <span class="finance-badge-method badge-method-cod">COD</span>
                                @elseif($key === 'momo')
                                    <span class="finance-badge-method badge-method-momo">MoMo</span>
                                @else
                                    <span class="finance-badge-method badge-method-unknown">Chưa xác định</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ number_format($orderCount) }}</td>
                            <td class="fw-semibold">{{ number_format($totalVal, 0, ',', '.') }} đ</td>
                            <td class="fw-bold text-success">{{ number_format($paidVal, 0, ',', '.') }} đ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
