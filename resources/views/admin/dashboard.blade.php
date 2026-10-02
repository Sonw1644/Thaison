@extends('layouts.admin')

@section('title', 'Bảng Điều Khiển - Thái Sơn')
@section('page_title', 'Bảng Điều Khiển Quản Trị')

@section('content')
<!-- Welcome Banner -->
<div class="card border-0 shadow-sm rounded-4 p-4 text-white mb-2" style="background: linear-gradient(135deg, #180f0b 0%, #261810 50%, #3e281d 100%); border: 1px solid #5a3e30 !important;">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <span class="badge px-3 py-1 rounded-pill fw-bold mb-2 text-white" style="background: rgba(224, 169, 109, 0.25); border: 1px solid rgba(224, 169, 109, 0.4); color: #e0a96d !important;">
                <i class="fa-solid fa-crown me-1 text-warning"></i> THÁI SƠN 
            </span>
            <h2 class="fw-bold mb-1 fs-3">Xin chào, {{ (Auth::user()->name === 'Admin User') ? 'Admin' : (Auth::user()->name ?? 'Admin') }}!</h2>
            <p class="text-light text-opacity-75 mb-0 small">
                Chào mừng bạn quay trở lại hệ thống quản trị <strong>Thái Sơn</strong>. Dưới đây là tình hình hoạt động đơn hàng, doanh thu và kho hàng hôm nay.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary fw-bold px-3 py-2 rounded-3 text-white shadow-sm">
                <i class="fa-solid fa-receipt me-1"></i> Quản Lý Đơn Hàng
            </a>
            <a href="{{ route('welcome') }}" target="_blank" class="btn btn-outline-light px-3 py-2 rounded-3">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Xem Web Khách
            </a>
        </div>
    </div>
</div>

<!-- 4 Key Statistic Cards (Đơn hàng, Doanh thu, Sản phẩm, Khách hàng) -->
<div class="row g-3">
    <!-- Total Revenue -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 position-relative overflow-hidden">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Doanh Thu Đã Thu</span>
                    <h3 class="fw-bold text-success mt-2 mb-0">{{ number_format($totalRevenue ?? 0, 0, ',', '.') }} đ</h3>
                    <div class="small text-success mt-2 fw-semibold">
                        <i class="fa-solid fa-money-bill-wave me-1"></i>Đơn đã thanh toán
                    </div>
                </div>
                <div class="p-3 rounded-4" style="background-color: #dcfce7; color: #15803d;">
                    <i class="fa-solid fa-sack-dollar fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 position-relative overflow-hidden">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Tổng Đơn Hàng</span>
                    <h3 class="fw-bold text-dark mt-2 mb-0">{{ number_format($orderCount ?? 0) }}</h3>
                    <div class="small text-warning mt-2 fw-semibold">
                        <i class="fa-solid fa-clock me-1"></i>{{ $pendingOrderCount ?? 0 }} đơn chờ xử lý
                    </div>
                </div>
                <div class="p-3 rounded-4" style="background-color: #fef3c7; color: #d97706;">
                    <i class="fa-solid fa-cart-flatbed fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Products -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 position-relative overflow-hidden" style="border: 1px solid var(--border-color) !important;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Sản Phẩm & Kho</span>
                    <h3 class="fw-bold text-dark mt-2 mb-0">{{ number_format($productCount ?? 0) }}</h3>
                    <div class="small mt-2 fw-semibold" style="color: #8b5e3c;">
                        <i class="fa-solid fa-boxes-stacked me-1"></i>{{ number_format($totalStock ?? 0) }} tồn kho
                    </div>
                </div>
                <div class="p-3 rounded-4" style="background-color: #f5efe6; color: #8b5e3c;">
                    <i class="fa-solid fa-couch fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 position-relative overflow-hidden" style="border: 1px solid var(--border-color) !important;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Khách Hàng / Users</span>
                    <h3 class="fw-bold text-dark mt-2 mb-0">{{ number_format($userCount ?? 0) }}</h3>
                    <div class="small mt-2 fw-semibold" style="color: #b45309;">
                        <i class="fa-solid fa-user-check me-1"></i>Tài khoản hệ thống
                    </div>
                </div>
                <div class="p-3 rounded-4" style="background-color: #fef3c7; color: #b45309;">
                    <i class="fa-solid fa-users fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section: Recent Orders Table -->
<div class="admin-card mt-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-receipt text-primary"></i>
            <h5 class="fw-bold mb-0 text-dark">Đơn Hàng Mới Nhất</h5>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary fw-semibold">
            Xem tất cả đơn hàng ({{ $orderCount ?? 0 }}) <i class="fa-solid fa-chevron-right ms-1"></i>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-custom-head">
                <tr>
                    <th style="width: 140px;">Mã Đơn</th>
                    <th>Khách Hàng & SĐT</th>
                    <th class="text-end">Tổng Tiền</th>
                    <th class="text-center">Hình Thức</th>
                    <th class="text-center">Thanh Toán</th>
                    <th class="text-center">Trạng Thái</th>
                    <th class="text-center" style="width: 80px;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders ?? [] as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold font-monospace text-primary text-decoration-none">
                                {{ $order->order_code }}
                            </a>
                            <div class="small text-muted" style="font-size: 0.72rem;">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $order->customer_name }}</div>
                            <div class="small text-muted">{{ $order->customer_phone }}</div>
                        </td>
                        <td class="text-end">
                            <span class="fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }} đ</span>
                        </td>
                        <td class="text-center">
                            @if($order->payment_method === 'banking')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem;">VietQR</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.72rem;">COD</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $order->payment_status_badge_class }}" style="font-size: 0.72rem;">
                                {{ $order->payment_status_label }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $order->status_badge_class }}" style="font-size: 0.75rem;">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-action-edit" title="Chi tiết đơn">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            Chưa có đơn hàng nào được tạo.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Tables: Recent Products & Categories -->
<div class="row g-4 mt-1">
    <!-- Recent Products Table -->
    <div class="col-xl-8">
        <div class="admin-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-couch text-primary"></i>
                    <h5 class="fw-bold mb-0 text-dark">Sản Phẩm Mới Cập Nhật</h5>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary fw-semibold">
                    Xem tất cả ({{ $productCount }}) <i class="fa-solid fa-chevron-right ms-1"></i>
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-custom-head">
                        <tr>
                            <th style="width: 70px;">Hình Ảnh</th>
                            <th>Thông Tin Sản Phẩm</th>
                            <th>Danh Mục</th>
                            <th>Giá Bán</th>
                            <th>Tồn Kho</th>
                            <th class="text-center" style="width: 80px;">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentProducts as $prod)
                            <tr>
                                <td>
                                    @if($prod->thumbnail)
                                        <img src="{{ $prod->thumbnail }}" alt="{{ $prod->name }}" class="rounded-3 shadow-sm" style="width: 48px; height: 48px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="width: 48px; height: 48px;">
                                            <i class="fa-regular fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $prod->id) }}" class="text-decoration-none text-dark fw-bold d-block text-truncate" style="max-width: 220px;">
                                        {{ $prod->name }}
                                    </a>
                                    <span class="badge-sku">{{ $prod->sku }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $prod->category->name ?? 'Chưa phân loại' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-primary">
                                        {{ number_format($prod->price, 0, ',', '.') }} đ
                                    </span>
                                </td>
                                <td>
                                    @if($prod->stock <= 0)
                                        <span class="badge badge-stock danger">Hết hàng</span>
                                    @elseif($prod->stock <= 50)
                                        <span class="badge badge-stock warning">{{ $prod->stock }}</span>
                                    @else
                                        <span class="badge badge-stock">{{ $prod->stock }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.products.edit', $prod->id) }}" class="btn-action-edit" title="Chỉnh sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Chưa có sản phẩm nào trong hệ thống.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Category Summary -->
    <div class="col-xl-4">
        <!-- Categories List Card -->
        <div class="admin-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-list-ul text-primary"></i>
                    <h5 class="fw-bold mb-0 text-dark">Danh Mục Hàng</h5>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-primary fw-semibold">
                    Quản lý
                </a>
            </div>

            <div class="list-group list-group-flush">
                @forelse($recentCategories as $category)
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-folder text-primary opacity-75"></i>
                            <span class="fw-semibold text-dark">{{ $category->name }}</span>
                        </div>
                        <span class="badge-count-products">
                            {{ $category->products_count }} sản phẩm
                        </span>
                    </div>
                @empty
                    <div class="text-center py-3 text-muted">Chưa có danh mục nào.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection