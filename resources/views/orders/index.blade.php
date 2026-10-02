@extends('layouts.app')

@section('title', 'Đơn Hàng Của Tôi - Thái Sơn Tiles')

@section('styles')
<style>
    .order-card {
        border-radius: 16px;
        border: 1px solid var(--border-color);
        background: #ffffff;
        transition: all 0.2s ease;
    }
    .order-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        border-color: #cbd5e1;
    }
    .nav-pills-custom .nav-link {
        color: #475569;
        font-weight: 600;
        border-radius: 10px;
        padding: 8px 16px;
        transition: all 0.2s;
    }
    .nav-pills-custom .nav-link.active {
        background: linear-gradient(135deg, #261810 0%, #3d281d 50%, #4a3528 100%) !important;
        border: 1px solid #6c4e31 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(38, 24, 16, 0.3);
    }
    .nav-pills-custom .nav-link.active .badge {
        background-color: rgba(224, 169, 109, 0.25) !important;
        color: #e0a96d !important;
        border: 1px solid rgba(224, 169, 109, 0.4) !important;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white p-3 rounded-3 border shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none text-primary"><i class="fa-solid fa-house me-1"></i> Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fa-solid fa-clock-rotate-left me-1"></i> Đơn hàng của tôi</li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="fa-solid fa-box-open text-primary me-2"></i>Lịch sử đơn hàng của tôi
            </h1>
            <p class="text-muted mb-0 small">Theo dõi quá trình vận chuyển, kiểm tra tình trạng thanh toán và chi tiết các đơn hàng.</p>
        </div>
        <a href="{{ route('welcome') }}" class="btn btn-outline-primary btn-sm">
            <i class="fa-solid fa-plus me-1"></i> Mua sắm thêm
        </a>
    </div>

    <!-- Filter Tabs & Search -->
    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
        <div class="row g-3 align-items-center justify-content-between">
            <!-- Tabs trạng thái -->
            <div class="col-lg-8">
                <ul class="nav nav-pills nav-pills-custom gap-1 overflow-x-auto pb-1 flex-nowrap flex-lg-wrap">
                    @php $currentStatus = request('status', 'all'); @endphp
                    <li class="nav-item">
                        <a class="nav-link {{ $currentStatus === 'all' ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'all', 'search' => request('search')]) }}">
                            Tất cả <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1">{{ $counts['all'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ in_array($currentStatus, ['pending', 'cod_ordered']) ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'pending', 'search' => request('search')]) }}">
                            Chờ xử lý <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill ms-1">{{ $counts['pending'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ in_array($currentStatus, ['shipping', 'delivering', 'processing']) ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'shipping', 'search' => request('search')]) }}">
                            Đang giao <span class="badge bg-info-subtle text-info rounded-pill ms-1">{{ $counts['shipping'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ in_array($currentStatus, ['completed', 'paid']) ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'completed', 'search' => request('search')]) }}">
                            Đã hoàn thành <span class="badge bg-success-subtle text-success rounded-pill ms-1">{{ $counts['completed'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentStatus === 'cancelled' ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'cancelled', 'search' => request('search')]) }}">
                            Hủy đơn <span class="badge bg-danger-subtle text-danger rounded-pill ms-1">{{ $counts['cancelled'] ?? 0 }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Ô tìm kiếm mã đơn -->
            <div class="col-lg-4">
                <form action="{{ route('orders.index') }}" method="GET" class="d-flex gap-2">
                    <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Tìm theo mã đơn...">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Danh sách đơn hàng -->
    @if($orders->count() > 0)
        <div class="d-flex flex-column gap-3">
            @foreach($orders as $order)
                <div class="order-card p-4">
                    <!-- Header của card đơn hàng -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pb-3 mb-3 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <span class="font-monospace fw-bold text-dark fs-6">
                                <i class="fa-solid fa-receipt text-primary me-1"></i>{{ $order->order_code }}
                            </span>
                            <span class="text-muted small">
                                <i class="fa-regular fa-calendar-days me-1"></i>{{ $order->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge {{ $order->payment_status_badge_class }} px-2 py-1 small">
                                <i class="fa-solid fa-credit-card me-1"></i>{{ $order->payment_status_label }}
                            </span>
                            <span class="badge {{ $order->status_badge_class }} px-3 py-1 fw-bold">
                                {{ $order->status_label }}
                            </span>
                        </div>
                    </div>

                    <!-- Danh sách các mặt hàng tóm tắt -->
                    <div class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex flex-column gap-2">
                                @foreach($order->items->take(2) as $item)
                                    <div class="d-flex align-items-center gap-3">
                                        @if($item->thumbnail)
                                            <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->product_name }}" class="rounded border object-fit-cover shadow-sm flex-shrink-0" style="width: 44px; height: 44px;">
                                        @else
                                            <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted flex-shrink-0" style="width: 44px; height: 44px;">
                                                <i class="fa-solid fa-image"></i>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <div class="fw-semibold text-dark text-truncate small">{{ $item->product_name }}</div>
                                            <div class="small text-muted">
                                                @if($item->variant_color_name)
                                                    <span class="text-primary me-2">Màu: {{ $item->variant_color_name }}</span>
                                                @endif
                                                <span>Số lượng: <strong>x{{ $item->quantity }}</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if($order->items->count() > 2)
                                    <div class="small text-muted fst-italic ps-1">
                                        + và {{ $order->items->count() - 2 }} sản phẩm khác...
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Cột Tổng tiền & Nút xem chi tiết -->
                        <div class="col-md-4 text-md-end border-top border-md-top-0 pt-3 pt-md-0">
                            <div class="mb-2">
                                <span class="text-muted small">Tổng thanh toán:</span>
                                <div class="h5 fw-bold text-danger mb-0">
                                    {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
                                </div>
                            </div>
                            <div class="d-flex justify-content-md-end gap-2 mt-3">
                                @if($order->payment_status !== 'paid' && $order->status !== 'cancelled')
                                    <a href="{{ route('orders.momo.pay', $order->id) }}" class="btn btn-sm text-white" style="background-color: #a50064;" title="Thanh toán qua Ví MoMo">
                                        <i class="fa-solid fa-credit-card me-1"></i> Trả MoMo
                                    </a>
                                @endif
                                @if($order->status === 'pending')
                                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng {{ $order->order_code }} không?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa-solid fa-ban me-1"></i> Hủy đơn
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('orders.show', $order->order_code) }}" class="btn btn-sm btn-primary px-3">
                                    <i class="fa-solid fa-eye me-1"></i> Chi tiết đơn
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Phân trang -->
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    @else
        <!-- Trạng thái không có đơn hàng -->
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4">
            <div class="my-4 text-muted" style="font-size: 4rem; opacity: 0.3;">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <h4 class="fw-bold text-dark">Chưa tìm thấy đơn hàng nào.</h4>
            <p class="text-muted mb-4">Bạn chưa có đơn hàng nào trong mục này hoặc chưa thực hiện giao dịch.</p>
            <div>
                <a href="{{ route('welcome') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold">
                    <i class="fa-solid fa-bag-shopping me-1"></i> Khám phá sản phẩm ngay
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
