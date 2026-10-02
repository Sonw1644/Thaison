@extends('layouts.admin')

@section('title', 'Giao Dịch Thanh Toán - Quản Trị')
@section('page_title', 'Giao dịch thanh toán')

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

    /* Bảng giao dịch */
    .finance-table {
        table-layout: fixed;
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .finance-table thead th {
        background-color: #fbf9f6 !important;
        color: #3d281d !important;
        font-size: 0.85rem !important;
        font-weight: 700 !important;
        padding: 12px 14px !important;
        border-bottom: 1px solid #ebd9c8 !important;
        vertical-align: middle;
    }
    .finance-table tbody td {
        font-size: 0.85rem !important;
        color: #261810 !important;
        padding: 12px 14px !important;
        border-bottom: 1px solid #f2ece4 !important;
        vertical-align: middle;
        word-wrap: break-word;
    }
    .finance-table tbody tr:hover td {
        background-color: rgba(229, 178, 121, 0.08) !important;
    }

    /* Nút lưu COD */
    .btn-cod-save {
        background: linear-gradient(135deg, #261810 0%, #3d281d 100%) !important;
        border: 1px solid #6c4e31 !important;
        color: #ffffff !important;
        font-size: 0.8rem !important;
        font-weight: 600 !important;
        height: 32px !important;
        padding: 0 14px !important;
        border-radius: 6px !important;
        box-shadow: none !important;
        transition: all 0.2s ease !important;
    }
    .btn-cod-save:hover {
        background: linear-gradient(135deg, #3d281d 0%, #503527 100%) !important;
        border-color: #d4a373 !important;
        color: #ffffff !important;
    }

    /* Badges phương thức & trạng thái */
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
    .payment-status-badge {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-block;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0 finance-wrapper">
    <!-- Breadcrumb & Header Title -->
    <div class="mb-3">
        <div class="text-secondary small mb-1" style="font-size: 0.8rem;">
            Quản trị / Giao dịch thanh toán
        </div>
        <h4 class="fw-bold mb-1" style="color: #261810; font-size: 1.35rem;">Giao dịch thanh toán</h4>
        <div class="text-secondary small">Tra cứu thanh toán theo đơn hàng và cập nhật trạng thái COD.</div>
    </div>

    <!-- Tabs chuyển đổi giữa 2 trang -->
    <div class="finance-tabs">
        <a href="{{ route('admin.finance.index') }}" class="finance-tab-item inactive">
            Thống kê chỉ số
        </a>
        <a href="{{ route('admin.finance.transactions') }}" class="finance-tab-item active">
            Giao dịch thanh toán
        </a>
    </div>

    <!-- Khối bộ lọc tìm kiếm -->
    <div class="finance-card">
        <form action="{{ route('admin.finance.transactions') }}" method="GET">
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

            <!-- Dòng 3: Sắp xếp và nút bấm căn chuẩn hàng ngang -->
            <div class="row g-3 align-items-end">
                <div class="col-md-4 col-lg-3">
                    <label class="finance-filter-label">Sắp xếp</label>
                    <select name="sort" class="form-select finance-input">
                        <option value="newest" {{ ($filters['sort'] ?? 'newest') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="oldest" {{ ($filters['sort'] ?? '') === 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                        <option value="amount_asc" {{ ($filters['sort'] ?? '') === 'amount_asc' ? 'selected' : '' }}>Số tiền tăng dần</option>
                        <option value="amount_desc" {{ ($filters['sort'] ?? '') === 'amount_desc' ? 'selected' : '' }}>Số tiền giảm dần</option>
                    </select>
                </div>
                <div class="col-md-8 col-lg-9 d-flex gap-2">
                    <button type="submit" class="btn btn-finance-primary">Áp dụng bộ lọc</button>
                    <a href="{{ route('admin.finance.transactions') }}" class="btn btn-finance-outline">Xóa bộ lọc</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Thông báo kết quả lọc -->
    <div class="text-secondary small mb-3">
        Có <strong>{{ number_format($orders->total()) }}</strong> đơn phù hợp bộ lọc. Số tiền bao gồm phí vận chuyển; ngày lọc là ngày tạo đơn.
    </div>

    <!-- Danh sách giao dịch -->
    <div class="finance-card px-0 py-3">
        <div class="px-3 mb-3">
            <h5 class="fw-bold mb-1" style="color: #261810; font-size: 1rem;">Danh sách giao dịch ({{ number_format($orders->total()) }} đơn)</h5>
            <div class="text-secondary" style="font-size: 0.8rem;">
                COD: xác nhận thu tiền hoặc thất bại; đơn đã thu tiền có thể chuyển sang chờ hoàn tiền rồi xác nhận đã hoàn tiền.
            </div>
        </div>

        <div class="table-responsive">
            <table class="table finance-table align-middle">
                <thead>
                    <tr>
                        <th style="width: 17%;">Đơn hàng</th>
                        <th style="width: 20%;">Khách hàng</th>
                        <th style="width: 14%;">Phương thức</th>
                        <th style="width: 14%;">Số tiền</th>
                        <th style="width: 15%;">Thanh toán</th>
                        <th style="width: 20%;">Cập nhật COD</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <!-- 1. Đơn hàng -->
                            <td>
                                <div>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-semibold text-decoration-none" style="color: #8b5e3c;">
                                        {{ $order->order_code ?? ('#' . $order->id) }}
                                    </a>
                                </div>
                                <div class="text-secondary small mt-1" style="font-size: 0.78rem;">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
                                </div>
                            </td>

                            <!-- 2. Khách hàng -->
                            <td>
                                <div class="fw-semibold text-dark">{{ $order->name ?? $order->customer_name ?? '—' }}</div>
                                <div class="text-secondary small mt-1" style="font-size: 0.78rem;">
                                    {{ $order->phone ?? $order->customer_phone ?? '—' }}
                                </div>
                            </td>

                            <!-- 3. Phương thức -->
                            <td>
                                @if($order->gateway === 'cod')
                                    <span class="finance-badge-method badge-method-cod">COD</span>
                                @elseif($order->gateway === 'momo')
                                    <span class="finance-badge-method badge-method-momo">MoMo</span>
                                @else
                                    <span class="finance-badge-method badge-method-unknown">Chưa xác định</span>
                                @endif
                            </td>

                            <!-- 4. Số tiền -->
                            <td>
                                <span class="fw-bold" style="color: #261810;">{{ number_format($order->total_price ?? $order->total_amount ?? 0, 0, ',', '.') }} đ</span>
                            </td>

                            <!-- 5. Thanh toán -->
                            <td>
                                @php
                                    $pStatus = $order->payment_status;
                                    $badgeClass = match($pStatus) {
                                        'paid' => 'bg-success-subtle text-success border border-success-subtle',
                                        'pending' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                                        'initiated' => 'bg-info-subtle text-info-emphasis border border-info-subtle',
                                        'failed' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                        'cancelled' => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                                        'refund_pending' => 'bg-warning text-dark border',
                                        'refunded' => 'bg-secondary text-white border',
                                        default => 'bg-light text-dark border'
                                    };
                                @endphp
                                <span class="payment-status-badge {{ $badgeClass }}">
                                    {{ $statuses[$pStatus] ?? $pStatus }}
                                </span>
                                @if($order->paid_at)
                                    <div class="text-success small mt-1" style="font-size: 0.75rem;">
                                        <i class="fa-regular fa-clock me-1"></i>{{ \Carbon\Carbon::parse($order->paid_at)->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                            </td>

                            <!-- 6. Cập nhật COD -->
                            <td>
                                @if($order->gateway === 'cod')
                                    @php
                                        $allowed = $codTransitions[$order->payment_status] ?? [];
                                    @endphp
                                    @if(count($allowed) > 0)
                                        <form action="{{ route('admin.finance.update-status', $order->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="current_payment_status" value="{{ $order->payment_status }}">
                                            <input type="hidden" name="current_order_status" value="{{ $order->status }}">
                                            <input type="hidden" name="current_payment_id" value="{{ $order->payment_id ?? 0 }}">
                                            <select name="payment_status" class="form-select form-select-sm finance-input" style="height: 32px !important; font-size: 0.82rem !important; padding: 2px 8px !important; border-radius: 6px !important;">
                                                @foreach($allowed as $transKey)
                                                    <option value="{{ $transKey }}" {{ $transKey === $order->payment_status ? 'selected' : '' }}>
                                                        {{ $statuses[$transKey] ?? $transKey }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-cod-save" onclick="return confirm('Bạn có chắc muốn lưu trạng thái thanh toán đơn #{{ $order->id }}?')">
                                                Lưu
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Đã chốt ({{ $statuses[$order->payment_status] ?? $order->payment_status }})</span>
                                    @endif
                                @else
                                    <span class="text-secondary small">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-secondary">
                                Không có đơn hàng phù hợp với bộ lọc.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="px-3 mt-4 d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
