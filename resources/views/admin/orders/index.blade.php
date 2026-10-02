@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng - Thái Sơn Admin')
@section('page_title', 'Quản Lý Đơn Hàng')

@section('styles')
<style>
    .order-status-badge {
        font-size: 0.78rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 20px;
    }
    .tr-selected {
        background-color: rgba(229, 178, 121, 0.12) !important;
    }
</style>
@endsection

@section('content')
<!-- Header Actions & Search -->
<div class="admin-card mb-4">
    <div class="row g-3 align-items-center justify-content-between">
        <!-- Bộ lọc Tabs trạng thái -->
        <div class="col-xl-8">
            <div class="d-flex flex-wrap gap-2">
                @php $curStatus = request('status', 'all'); @endphp
                <a href="{{ route('admin.orders.index', ['status' => 'all', 'payment_status' => request('payment_status'), 'search' => request('search')]) }}" 
                   class="btn btn-sm {{ $curStatus === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    Tất cả ({{ $counts['all'] ?? 0 }})
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'pending', 'payment_status' => request('payment_status'), 'search' => request('search')]) }}" 
                   class="btn btn-sm {{ in_array($curStatus, ['pending', 'cod_ordered']) ? 'btn-warning text-dark' : 'btn-outline-warning text-dark' }}">
                    Chờ xử lý ({{ $counts['pending'] ?? 0 }})
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'shipping', 'payment_status' => request('payment_status'), 'search' => request('search')]) }}" 
                   class="btn btn-sm {{ in_array($curStatus, ['shipping', 'delivering', 'processing']) ? 'btn-info text-dark' : 'btn-outline-info text-dark' }}">
                    Đang giao ({{ $counts['shipping'] ?? 0 }})
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'completed', 'payment_status' => request('payment_status'), 'search' => request('search')]) }}" 
                   class="btn btn-sm {{ in_array($curStatus, ['completed', 'paid']) ? 'btn-success' : 'btn-outline-success' }}">
                    Đã hoàn thành ({{ $counts['completed'] ?? 0 }})
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'cancelled', 'payment_status' => request('payment_status'), 'search' => request('search')]) }}" 
                   class="btn btn-sm {{ $curStatus === 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }}">
                    Hủy đơn ({{ $counts['cancelled'] ?? 0 }})
                </a>
            </div>
        </div>

        <!-- Tìm kiếm -->
        <div class="col-xl-4">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex gap-2">
                <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Tìm theo mã đơn, tên khách, SĐT...">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Form xử lý hàng loạt theo Checkbox -->
<form id="bulkOrdersForm" action="{{ route('admin.orders.confirmDeliveredBulk') }}" method="POST">
    @csrf
    <!-- Action Toolbar cho các đơn đã chọn -->
    <div class="admin-card mb-3 py-2 px-3 bg-light d-flex flex-wrap align-items-center justify-content-between gap-2 border">
        <div class="d-flex align-items-center gap-2">
            <span class="small fw-semibold text-muted">Đã chọn: <strong id="selectedCountText" class="text-primary">0</strong> đơn hàng</span>
        </div>
        <div>
            <button type="submit" id="btnConfirmDelivered" class="btn btn-sm btn-success fw-bold d-inline-flex align-items-center gap-1 shadow-sm" disabled onclick="return confirm('Bạn có chắc chắn muốn xác nhận ĐÃ GIAO (Hoàn thành) cho các đơn hàng đã chọn?')">
                <i class="fa-solid fa-circle-check"></i> Xác nhận đã giao (<span id="selectedCount">0</span>)
            </button>
        </div>
    </div>

    <!-- Table Orders -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-custom-head">
                    <tr>
                        <th style="width: 40px;" class="text-center">
                            <input type="checkbox" id="selectAllOrders" class="form-check-input" title="Chọn tất cả">
                        </th>
                        <th style="width: 140px;">Mã Đơn Hàng</th>
                        <th>Khách Hàng & SĐT</th>
                        <th>Địa Chỉ Nhận Hàng</th>
                        <th class="text-end">Tổng Tiền</th>
                        <th class="text-center">Phương Thức</th>
                        <th class="text-center">Thanh Toán</th>
                        <th class="text-center">Trạng Thái</th>
                        <th class="text-center" style="width: 120px;">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr id="order-row-{{ $order->id }}">
                            <!-- Checkbox chọn đơn -->
                            <td class="text-center">
                                <input type="checkbox" name="order_ids[]" value="{{ $order->id }}" class="form-check-input order-checkbox">
                            </td>

                            <!-- Mã đơn -->
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold font-monospace text-primary text-decoration-none">
                                    {{ $order->order_code }}
                                </a>
                                <div class="small text-muted" style="font-size: 0.75rem;">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </div>
                            </td>

                            <!-- Khách hàng -->
                            <td>
                                <div class="fw-bold text-dark">{{ $order->customer_name }}</div>
                                <div class="small text-muted">
                                    <i class="fa-solid fa-phone me-1"></i>{{ $order->customer_phone }}
                                </div>
                            </td>

                            <!-- Địa chỉ -->
                            <td>
                                <span class="small text-dark text-truncate d-inline-block" style="max-width: 220px;" title="{{ $order->full_address }}">
                                    {{ $order->full_address }}
                                </span>
                            </td>

                            <!-- Tổng tiền -->
                            <td class="text-end">
                                <span class="fw-bold text-danger">
                                    {{ number_format($order->total_amount, 0, ',', '.') }} đ
                                </span>
                                <div class="text-muted small" style="font-size: 0.75rem;">
                                    {{ $order->items->sum('quantity') }} sản phẩm
                                </div>
                            </td>

                            <!-- Phương thức -->
                            <td class="text-center">
                                @if($order->payment_method === 'banking')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-qrcode me-1"></i>VietQR
                                    </span>
                                @elseif($order->payment_method === 'momo')
                                    <span class="badge text-white px-2 py-1 shadow-sm" style="background-color: #a50064; font-size: 0.75rem;">
                                        <i class="fa-solid fa-wallet me-1"></i>MoMo
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-truck-ramp-box me-1"></i>COD
                                    </span>
                                @endif
                            </td>

                            <!-- Thanh toán -->
                            <td class="text-center">
                                <span class="badge {{ $order->payment_status_badge_class }} px-2 py-1" style="font-size: 0.75rem;">
                                    {{ $order->payment_status_label }}
                                </span>
                            </td>

                            <!-- Trạng thái đơn -->
                            <td class="text-center">
                                <span class="badge {{ $order->status_badge_class }} px-2 py-1 fw-bold" style="font-size: 0.78rem;">
                                    {{ $order->status_label }}
                                </span>
                            </td>

                            <!-- Thao tác -->
                            <td class="text-center">
                                <div class="d-inline-flex gap-1">
                                    @if(!in_array($order->status, ['completed', 'cancelled']))
                                        <form action="{{ route('admin.orders.confirmDeliveredSingle', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận đơn hàng {{ $order->order_code }} đã giao thành công (Hoàn thành)?');">
                                            @csrf
                                            <button type="submit" class="btn-action-edit text-success border-success" title="Xác nhận đã giao">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-action-edit" title="Xem chi tiết & Xử lý đơn">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fs-2 mb-2 d-block opacity-50"></i>
                                Chưa có đơn hàng nào trong danh mục này.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('selectAllOrders');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const btnConfirmDelivered = document.getElementById('btnConfirmDelivered');
    const selectedCountSpan = document.getElementById('selectedCount');
    const selectedCountText = document.getElementById('selectedCountText');

    function updateSelection() {
        let count = 0;
        orderCheckboxes.forEach(cb => {
            const row = document.getElementById('order-row-' + cb.value);
            if (cb.checked) {
                count++;
                if (row) row.classList.add('tr-selected');
            } else {
                if (row) row.classList.remove('tr-selected');
            }
        });

        if (selectedCountSpan) selectedCountSpan.textContent = count;
        if (selectedCountText) selectedCountText.textContent = count;

        if (btnConfirmDelivered) {
            btnConfirmDelivered.disabled = (count === 0);
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = (orderCheckboxes.length > 0 && count === orderCheckboxes.length);
            selectAllCheckbox.indeterminate = (count > 0 && count < orderCheckboxes.length);
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            orderCheckboxes.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
            });
            updateSelection();
        });
    }

    orderCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateSelection);
    });

    updateSelection();
});
</script>
@endsection
