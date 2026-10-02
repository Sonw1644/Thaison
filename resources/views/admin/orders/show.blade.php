@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng ' . $order->order_code . ' - Thái Sơn Admin')
@section('page_title', 'Chi Tiết Đơn Hàng: ' . $order->order_code)

@section('styles')
<style>
    @media print {
        .admin-sidebar, .top-header-card, .no-print, .btn-action-group {
            display: none !important;
        }
        .admin-main {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .admin-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
    }
</style>
@endsection

@section('content')
<!-- Back button & actions -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3 no-print">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại danh sách đơn hàng
    </a>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-sm btn-outline-primary">
            <i class="fa-solid fa-print me-1"></i> In hóa đơn giao hàng
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Cột Trái: Chi tiết sản phẩm & Tổng kết hóa đơn -->
    <div class="col-lg-8">
        <div class="admin-card mb-4">
            <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom">
                <div>
                    <h5 class="fw-bold text-dark mb-1">
                        <i class="fa-solid fa-receipt text-primary me-2"></i>Đơn Hàng #{{ $order->order_code }}
                    </h5>
                    <div class="text-muted small">
                        Ngày tạo: {{ $order->created_at->format('d/m/Y H:i:s') }}
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge {{ $order->payment_status_badge_class }} px-3 py-2 fs-6">
                        {{ $order->payment_status_label }}
                    </span>
                    <span class="badge {{ $order->status_badge_class }} px-3 py-2 fs-6">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>

            <!-- Bảng danh sách sản phẩm -->
            <div class="table-responsive mb-4">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-custom-head">
                        <tr>
                            <th style="width: 60px;">Hình</th>
                            <th>Tên Sản Phẩm</th>
                            <th class="text-center" style="width: 100px;">Số Lượng</th>
                            <th class="text-end" style="width: 140px;">Đơn Giá</th>
                            <th class="text-end" style="width: 150px;">Thành Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    @if($item->thumbnail)
                                        <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->product_name }}" class="rounded border object-fit-cover" style="width: 46px; height: 46px;">
                                    @else
                                        <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted" style="width: 46px; height: 46px;">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($item->product_id)
                                        <a href="{{ route('admin.products.edit', $item->product_id) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                                            {{ $item->product_name }}
                                        </a>
                                    @else
                                        <span class="fw-bold text-dark">{{ $item->product_name }}</span>
                                    @endif

                                    @if($item->variant_color_name)
                                        <div class="mt-1">
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size: 0.72rem;">
                                                <i class="fa-solid fa-palette me-1"></i> Vân / Màu: {{ $item->variant_color_name }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                <td class="text-end text-muted">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                <td class="text-end fw-bold text-primary">{{ number_format($item->subtotal, 0, ',', '.') }} đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tổng kết tiền -->
            <div class="row justify-content-end">
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded-3 border">
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>Tiền hàng:</span>
                            <span class="fw-semibold text-dark">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>Phí vận chuyển:</span>
                            <span class="text-success fw-semibold">Miễn phí (0 VNĐ)</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark">Tổng cộng:</span>
                            <span class="h5 fw-bold text-danger mb-0">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cột Phải: Cập nhật trạng thái đơn & Thông tin người nhận -->
    <div class="col-lg-4">
        <!-- Card cập nhật trạng thái -->
        <div class="admin-card mb-4 no-print">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="fa-solid fa-pen-to-square text-primary me-2"></i>Xử Lý & Cập Nhật Đơn
            </h5>

            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <!-- Trạng thái đơn hàng (3 trạng thái) -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Trạng thái đơn hàng:</label>
                    <select name="status" class="form-select fw-semibold">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Chờ xử lý (Pending)</option>
                        <option value="shipping" {{ in_array($order->status, ['shipping', 'delivering', 'processing']) ? 'selected' : '' }}>Đang giao (Shipping)</option>
                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Đã hoàn thành (Completed)</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Hủy đơn (Cancelled)</option>
                    </select>
                </div>

                <!-- Trạng thái thanh toán -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Trạng thái thanh toán:</label>
                    <select name="payment_status" class="form-select fw-semibold">
                        <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán (Unpaid)</option>
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Đã thanh toán (Paid)</option>
                        <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền (Refunded)</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cập Nhật
                </button>
            </form>
        </div>

        <!-- Thông tin khách hàng & Địa chỉ nhận hàng -->
        <div class="admin-card">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="fa-solid fa-user text-primary me-2"></i>Thông Tin Người Nhận
            </h5>

            <div class="d-flex flex-column gap-2 small">
                <div>
                    <span class="text-muted d-block">Họ và tên:</span>
                    <strong class="text-dark fs-6">{{ $order->customer_name }}</strong>
                </div>
                <div>
                    <span class="text-muted d-block">Số điện thoại:</span>
                    <strong class="text-dark font-monospace fs-6">
                        <i class="fa-solid fa-phone text-success me-1"></i>{{ $order->customer_phone }}
                    </strong>
                </div>
                @if($order->customer_email)
                    <div>
                        <span class="text-muted d-block">Email:</span>
                        <strong class="text-dark">{{ $order->customer_email }}</strong>
                    </div>
                @endif
                <div>
                    <span class="text-muted d-block">Địa chỉ giao hàng:</span>
                    <span class="fw-semibold text-dark">{{ $order->full_address }}</span>
                </div>
                <div>
                    <span class="text-muted d-block">Phương thức thanh toán:</span>
                    <span class="badge bg-light text-dark border">{{ $order->payment_method_name }}</span>
                </div>
                @if($order->notes)
                    <div class="border-top pt-2 mt-2">
                        <span class="text-muted d-block">Ghi chú từ khách:</span>
                        <em class="text-danger">"{{ $order->notes }}"</em>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
