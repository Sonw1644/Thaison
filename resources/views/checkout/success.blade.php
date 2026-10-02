@extends('layouts.app')

@section('title', 'Đặt Hàng Thành Công - Thái Sơn Tiles')

@section('styles')
<style>
    .success-card {
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        border: 1px solid var(--border-color);
    }
    .qr-box-container {
        background: linear-gradient(135deg, #fbf8f4 0%, #f5efe6 100%);
        border: 2px dashed #c48b52;
        border-radius: 16px;
        padding: 24px;
    }
    .qr-canvas-wrapper {
        background: #ffffff;
        padding: 14px;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        display: inline-block;
    }
    .bank-info-label {
        color: #64748b;
        font-size: 0.85rem;
    }
    .bank-info-val {
        font-weight: 700;
        color: #0f172a;
        font-size: 1.05rem;
    }
    .btn-copy {
        cursor: pointer;
        padding: 2px 8px;
        font-size: 0.75rem;
        border-radius: 6px;
    }
    @media print {
        .main-navbar, .footer, .no-print {
            display: none !important;
        }
        .container {
            max-width: 100% !important;
            padding: 0 !important;
        }
        .success-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4 no-print">
        <ol class="breadcrumb bg-white p-3 rounded-3 border shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none text-primary"><i class="fa-solid fa-house me-1"></i> Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none text-primary"><i class="fa-solid fa-cart-shopping me-1"></i> Giỏ hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fa-solid fa-circle-check text-success me-1"></i> Đặt hàng thành công</li>
        </ol>
    </nav>

    <div class="success-card p-4 p-md-5 mb-4">
        <!-- Header thông báo -->
        <div class="text-center mb-4 pb-3 border-bottom">
            <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-3 shadow" style="width: 70px; height: 70px; font-size: 2rem;">
                <i class="fa-solid fa-check"></i>
            </div>
            <h2 class="fw-bold text-dark mb-1">CẢM ƠN BẠN ĐÃ ĐẶT HÀNG!</h2>
            <p class="text-muted mb-2">Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.</p>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 bg-light rounded-pill border">
                <span class="text-muted small">Mã đơn hàng:</span>
                <strong class="text-primary font-monospace fs-5" id="orderCodeText">{{ $order->order_code }}</strong>
            </div>
        </div>

        <!-- Khung Thanh Toán Chuyển Khoản VietQR (Nếu chọn banking) -->
        @if($order->payment_method === 'banking')
            <!-- Khung khi ĐÃ THANH TOÁN -->
            <div id="paidSuccessCard" class="alert alert-success border-0 shadow-sm rounded-4 p-4 mb-4 {{ $order->payment_status === 'paid' ? '' : 'd-none' }}">
                <div class="d-flex align-items-center gap-3">
                    <i class="fa-solid fa-circle-check fs-1 text-success"></i>
                    <div>
                        <h4 class="fw-bold text-success mb-1">ĐÃ THANH TOÁN THÀNH CÔNG!</h4>
                        <p class="mb-0 text-dark">Hệ thống đã ghi nhận thanh toán cho đơn hàng <strong>{{ $order->order_code }}</strong>. Nhân viên sẽ liên hệ và giao hàng sớm nhất.</p>
                    </div>
                </div>
            </div>

            <!-- Khung khi CHƯA THANH TOÁN -->
            <div id="unpaidQrSection" class="qr-box-container mb-5 {{ $order->payment_status === 'paid' ? 'd-none' : '' }}">
                <div class="row align-items-center g-4">
                    <!-- Cột Mã QR chuẩn VietQR MBBank -->
                    <div class="col-md-5 text-center">
                        <div class="qr-canvas-wrapper text-center">
                            @php
                                $vietQrUrl = "https://img.vietqr.io/image/MB-011116042004-compact2.png?amount=" . (int)$order->total_amount . "&addInfo=" . urlencode($order->order_code) . "&accountName=" . urlencode("THAN THAI SON");
                            @endphp
                            
                            <img src="{{ $vietQrUrl }}" alt="Mã VietQR MBBank" class="img-fluid rounded shadow-sm border" style="max-width: 250px; height: auto;">

                            <div class="mt-2 text-muted fw-semibold" style="font-size: 0.8rem;">
                                <i class="fa-solid fa-qrcode me-1 text-primary"></i> Quét mã để thanh toán tự động
                            </div>
                        </div>
                    </div>

                    <!-- Cột Thông tin tài khoản & Nút Xác nhận đã chuyển -->
                    <div class="col-md-7">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge bg-primary text-white px-3 py-1 rounded-pill fw-semibold">
                                <i class="fa-solid fa-building-columns me-1"></i> Thông tin chuyển khoản
                            </span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <i class="fa-solid fa-bolt me-1"></i> Tự động nhận diện
                            </span>
                        </div>

                        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white mb-3">
                            <div class="row g-2 text-dark small">
                                <div class="col-sm-6">
                                    <div class="text-muted">Ngân hàng:</div>
                                    <strong class="text-primary fs-6">MBBank (Quân Đội)</strong>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted">Chủ tài khoản:</div>
                                    <strong class="text-uppercase text-dark fs-6">THAN THAI SON</strong>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted">Số tài khoản:</div>
                                    <strong class="text-danger font-monospace fs-5">011116042004</strong>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted">Số tiền:</div>
                                    <strong class="text-success fs-5">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Nút Xác nhận đã thanh toán -->
                        <div class="p-3 bg-white rounded-3 border border-success-subtle shadow-sm">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                <span class="fw-bold text-dark small">
                                    <i class="fa-solid fa-shield-check text-success me-1"></i> Bạn đã quét mã và chuyển khoản xong?
                                </span>
                                <span class="badge bg-warning-subtle text-warning-emphasis small" id="pollingIndicator">
                                    <span class="spinner-border spinner-border-sm me-1" style="width: 10px; height: 10px;"></span>Đang chờ thanh toán...
                                </span>
                            </div>
                            <button type="button" id="btnConfirmPaid" onclick="confirmPaymentAction()" class="btn btn-success w-100 py-2 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                                <i class="fa-solid fa-circle-check fs-5"></i>
                                <span>TÔI ĐÃ CHUYỂN KHOẢN XONG (XÁC NHẬN)</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- COD Banner -->
            <div class="alert alert-info border-0 shadow-sm rounded-4 p-4 mb-4 d-flex align-items-center gap-3">
                <i class="fa-solid fa-truck-fast fs-2 text-primary"></i>
                <div>
                    <h5 class="fw-bold mb-1">Phương thức thanh toán: Thanh toán khi nhận hàng (COD)</h5>
                    <p class="mb-0 small text-dark">
                        Bạn sẽ thanh toán số tiền <strong>{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</strong> bằng tiền mặt cho nhân viên giao hàng khi nhận được sản phẩm gạch ốp lát.
                    </p>
                </div>
            </div>
        @endif

        <!-- Chi tiết thông tin đơn hàng & Người nhận -->
        <div class="row g-4 mb-4">
            <!-- Thông tin người nhận -->
            <div class="col-md-6">
                <div class="card border rounded-3 p-3 bg-light h-100">
                    <h6 class="fw-bold text-dark mb-3">
                        <i class="fa-solid fa-user-tag text-primary me-2"></i>Thông tin người nhận hàng
                    </h6>
                    <ul class="list-unstyled mb-0 small d-flex flex-column gap-2">
                        <li><span class="text-muted">Người nhận:</span> <strong>{{ $order->customer_name }}</strong></li>
                        <li><span class="text-muted">Số điện thoại:</span> <strong>{{ $order->customer_phone }}</strong></li>
                        @if($order->customer_email)
                            <li><span class="text-muted">Email:</span> <strong>{{ $order->customer_email }}</strong></li>
                        @endif
                        <li><span class="text-muted">Địa chỉ nhận hàng:</span> <strong>{{ $order->full_address }}</strong></li>
                        @if($order->notes)
                            <li><span class="text-muted">Ghi chú:</span> <em>"{{ $order->notes }}"</em></li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Tình trạng đơn hàng -->
            <div class="col-md-6">
                <div class="card border rounded-3 p-3 bg-light h-100">
                    <h6 class="fw-bold text-dark mb-3">
                        <i class="fa-solid fa-clipboard-check text-primary me-2"></i>Trạng thái đơn hàng
                    </h6>
                    <ul class="list-unstyled mb-0 small d-flex flex-column gap-2">
                        <li><span class="text-muted">Ngày đặt hàng:</span> <strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong></li>
                        <li>
                            <span class="text-muted">Trạng thái đơn:</span> 
                            <span class="badge {{ $order->status_badge_class }} px-2 py-1" id="orderStatusBadge">{{ $order->status_label }}</span>
                        </li>
                        <li>
                            <span class="text-muted">Phương thức TT:</span> 
                            <strong>{{ $order->payment_method_name }}</strong>
                        </li>
                        <li>
                            <span class="text-muted">Trạng thái TT:</span> 
                            <span class="badge {{ $order->payment_status_badge_class }} px-2 py-1" id="paymentStatusBadge">{{ $order->payment_status_label }}</span>
                        </li>
                        @if($order->ghn_order_code)
                            <li>
                                <span class="text-muted">Mã vận đơn GHN:</span> 
                                <strong class="text-primary font-monospace">{{ $order->ghn_order_code }}</strong>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bảng danh sách sản phẩm trong đơn -->
        <h6 class="fw-bold text-dark mb-3">
            <i class="fa-solid fa-boxes-stacked text-primary me-2"></i>Chi tiết các mặt hàng đã đặt
        </h6>
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">STT</th>
                        <th>Sản phẩm</th>
                        <th class="text-center" style="width: 120px;">Số lượng</th>
                        <th class="text-end" style="width: 160px;">Đơn giá</th>
                        <th class="text-end" style="width: 170px;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $index => $item)
                        <tr>
                            <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($item->thumbnail)
                                        <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->product_name }}" class="rounded border object-fit-cover shadow-sm" style="width: 48px; height: 48px;">
                                    @else
                                        <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted" style="width: 48px; height: 48px;">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                                        @if($item->variant_color_name)
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.75rem;">
                                                <i class="fa-solid fa-palette me-1"></i> Vân / Màu: {{ $item->variant_color_name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center fw-bold">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                            <td class="text-end fw-bold text-primary">{{ number_format($item->subtotal, 0, ',', '.') }} đ</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @if($order->ghn_total_fee > 0)
                        <tr>
                            <td colspan="4" class="text-end text-muted">Phí vận chuyển (GHN):</td>
                            <td class="text-end fw-bold text-dark">{{ number_format($order->ghn_total_fee, 0, ',', '.') }} đ</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Tổng thanh toán:</td>
                        <td class="text-end fw-bold text-danger fs-5">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Các nút thao tác -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 border-top no-print">
            <a href="{{ route('welcome') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-bag-shopping me-1"></i> Tiếp tục mua sắm
            </a>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-outline-primary">
                    <i class="fa-solid fa-print me-1"></i> In hóa đơn
                </button>
                <a href="{{ route('orders.index') }}" class="btn btn-primary fw-bold shadow-sm">
                    <i class="fa-solid fa-clock-rotate-left me-1"></i> Xem danh sách đơn hàng của tôi
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    const orderCode = "{{ $order->order_code }}";
    const confirmUrl = "{{ route('orders.confirmPayment', $order->order_code) }}";
    const checkStatusUrl = "{{ route('orders.checkStatus', $order->order_code) }}";
    let isPaid = {{ $order->payment_status === 'paid' ? 'true' : 'false' }};

    // Hàm gọi khi nhấn "Tôi đã chuyển khoản xong"
    function confirmPaymentAction() {
        const btn = document.getElementById('btnConfirmPaid');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý xác nhận...';

        fetch(confirmUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                markAsPaidUI();
            } else {
                alert(data.message || 'Xác nhận thất bại');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-circle-check fs-5"></i><span>TÔI ĐÃ CHUYỂN KHOẢN XONG (XÁC NHẬN)</span>';
            }
        })
        .catch(err => {
            console.error(err);
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-circle-check fs-5"></i><span>TÔI ĐÃ CHUYỂN KHOẢN XONG (XÁC NHẬN)</span>';
        });
    }

    // Cập nhật giao diện sang ĐÃ THANH TOÁN
    function markAsPaidUI() {
        isPaid = true;
        const unpaidQrSection = document.getElementById('unpaidQrSection');
        const paidSuccessCard = document.getElementById('paidSuccessCard');
        const paymentStatusBadge = document.getElementById('paymentStatusBadge');

        if (unpaidQrSection) unpaidQrSection.classList.add('d-none');
        if (paidSuccessCard) paidSuccessCard.classList.remove('d-none');

        if (paymentStatusBadge) {
            paymentStatusBadge.className = 'badge bg-success-subtle text-success border border-success-subtle px-2 py-1';
            paymentStatusBadge.textContent = 'Đã thanh toán';
        }

        const orderStatusBadge = document.getElementById('orderStatusBadge');
        if (orderStatusBadge) {
            orderStatusBadge.className = 'badge bg-info-subtle text-info border border-info-subtle px-2 py-1';
            orderStatusBadge.textContent = 'Đang giao';
        }
    }

    // Tự động kiểm tra trạng thái mỗi 3 giây (polling)
    if (!isPaid) {
        const checkInterval = setInterval(() => {
            if (isPaid) {
                clearInterval(checkInterval);
                return;
            }
            fetch(checkStatusUrl, { credentials: 'same-origin' })
                .then(res => res.json())
                .then(data => {
                    if (data.is_paid) {
                        markAsPaidUI();
                        clearInterval(checkInterval);
                    }
                })
                .catch(err => console.error(err));
        }, 3000);
    }
</script>
@endsection
