@extends('layouts.app')

@section('title', 'Chi Tiết Đơn Hàng ' . $order->order_code . ' - Thái Sơn Tiles')

@section('styles')
<style>
    .order-stepper {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 24px 0 32px;
    }
    .stepper-line-bg {
        position: absolute;
        top: 22px;
        left: 16%;
        right: 16%;
        height: 4px;
        background: #e2e8f0;
        z-index: 1;
        border-radius: 2px;
    }
    .stepper-line-progress {
        position: absolute;
        top: 22px;
        left: 16%;
        max-width: 68%;
        height: 4px;
        background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        z-index: 1;
        border-radius: 2px;
        transition: width 0.4s ease;
    }
    .step-item {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }
    .step-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #ffffff;
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-bottom: 8px;
        border: 3px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: all 0.3s;
    }
    .step-item.completed .step-icon {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        border-color: #ffffff;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.35);
    }
    .step-item.active .step-icon {
        background: linear-gradient(135deg, #261810 0%, #3d281d 50%, #4a3528 100%);
        border-color: #ffffff;
        color: #e0a96d;
        box-shadow: 0 0 0 3px #e0a96d;
    }
    .step-label {
        font-size: 0.84rem;
        font-weight: 600;
        color: #64748b;
    }
    .step-item.active .step-label {
        color: #8b5e3c;
        font-weight: 700;
    }
    .step-item.completed .step-label {
        color: #059669;
        font-weight: 700;
    }
    @media print {
        .main-navbar, .footer, .no-print {
            display: none !important;
        }
        .container {
            max-width: 100% !important;
            padding: 0 !important;
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
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none text-primary"><i class="fa-solid fa-clock-rotate-left me-1"></i> Đơn hàng của tôi</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $order->order_code }}</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
        <!-- Top bar: Order header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pb-3 mb-4 border-bottom">
            <div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 fw-bold mb-2">
                    MÃ ĐƠN HÀNG
                </span>
                <h2 class="h3 fw-bold text-dark font-monospace mb-1">{{ $order->order_code }}</h2>
                <div class="text-muted small">
                    <i class="fa-regular fa-calendar me-1"></i> Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge {{ $order->payment_status_badge_class }} px-3 py-2 fs-6">
                    <i class="fa-solid fa-credit-card me-1"></i>{{ $order->payment_status_label }}
                </span>
                <span class="badge {{ $order->status_badge_class }} px-3 py-2 fs-6">
                    <i class="fa-solid fa-truck-fast me-1"></i>{{ $order->status_label }}
                </span>
            </div>
        </div>

        <!-- Trạng thái tiến trình đơn hàng (Stepper 3 bước: Chờ xử lý -> Đang giao hàng -> Đã hoàn thành) -->
        @if($order->status !== 'cancelled')
            <div class="order-stepper my-4 d-none d-sm-flex position-relative">
                <!-- Line background -->
                <div class="stepper-line-bg"></div>
                <!-- Line active progress -->
                @php
                    $progressWidth = '0%';
                    if (in_array($order->status, ['shipping', 'delivering', 'processing'])) {
                        $progressWidth = '50%';
                    } elseif ($order->status === 'completed') {
                        $progressWidth = '100%';
                    }
                @endphp
                <div class="stepper-line-progress" style="width: {{ $progressWidth }};"></div>

                <!-- Step 1: Chờ xử lý -->
                <div class="step-item {{ in_array($order->status, ['pending', 'cod_ordered']) ? 'active' : 'completed' }}">
                    <div class="step-icon">
                        @if(in_array($order->status, ['shipping', 'delivering', 'processing', 'completed']))
                            <i class="fa-solid fa-check"></i>
                        @else
                            <i class="fa-solid fa-file-invoice"></i>
                        @endif
                    </div>
                    <div class="step-label">Chờ xử lý / Tiếp nhận</div>
                </div>

                <!-- Step 2: Đang giao hàng -->
                <div class="step-item {{ in_array($order->status, ['shipping', 'delivering', 'processing']) ? 'active' : ($order->status === 'completed' ? 'completed' : '') }}">
                    <div class="step-icon">
                        @if($order->status === 'completed')
                            <i class="fa-solid fa-check"></i>
                        @else
                            <i class="fa-solid fa-truck-fast"></i>
                        @endif
                    </div>
                    <div class="step-label">Đang giao hàng</div>
                </div>

                <!-- Step 3: Đã hoàn thành -->
                <div class="step-item {{ $order->status === 'completed' ? 'completed active' : '' }}">
                    <div class="step-icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="step-label">Đã hoàn thành</div>
                </div>
            </div>

            <!-- Status Notice Message -->
            @if(in_array($order->status, ['shipping', 'delivering', 'processing']))
                <div class="alert alert-info border-0 rounded-3 p-3 mb-4 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-truck-fast fs-4 text-info"></i>
                    <div>
                        <strong>Đơn hàng đang trong quá trình vận chuyển!</strong>
                        <div class="small">Kiện hàng đã được đóng gói và bàn giao cho đơn vị vận chuyển. Khi nhận hàng thành công, Admin sẽ xác nhận hoàn thành đơn.</div>
                    </div>
                </div>
            @elseif($order->status === 'completed')
                <div class="alert alert-success border-0 rounded-3 p-3 mb-4 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-check fs-4 text-success"></i>
                    <div>
                        <strong>Đơn hàng đã hoàn thành!</strong>
                        <div class="small">Admin đã xác nhận giao hàng thành công. Cảm ơn quý khách đã tin tưởng và mua sắm tại Thái Sơn Tiles.</div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning border-0 rounded-3 p-3 mb-4 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-clock fs-4 text-warning"></i>
                    <div>
                        <strong>Đơn hàng đang chờ xử lý!</strong>
                        <div class="small">{{ $order->payment_status === 'paid' ? 'Đã nhận thanh toán. Đơn hàng đang chuẩn bị xuất kho giao cho bạn.' : 'Vui lòng hoàn tất thanh toán hoặc chuẩn bị tiền mặt khi nhận hàng (COD).' }}</div>
                    </div>
                </div>
            @endif
        @else
            <div class="alert alert-danger border-0 rounded-3 p-3 mb-4 d-flex align-items-center gap-2">
                <i class="fa-solid fa-circle-xmark fs-4"></i>
                <div>
                    <strong>Đơn hàng này đã bị hủy.</strong>
                    <div class="small">Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ hotline hỗ trợ khách hàng của chúng tôi.</div>
                </div>
            </div>
        @endif

        <!-- Nếu chọn banking mà chưa thanh toán, hiển thị mã QR thanh toán nhanh -->
        @if($order->payment_method === 'banking' && $order->payment_status === 'unpaid' && $order->status !== 'cancelled')
            <div class="p-4 rounded-4 bg-light border border-primary-subtle mb-4">
                <div class="row align-items-center g-4">
                    <div class="col-md-4 text-center">
                        <div class="bg-white p-3 rounded-4 shadow-sm d-inline-block text-center">
                            @php
                                $detailVietQrUrl = "https://img.vietqr.io/image/MB-011116042004-compact2.png?amount=" . (int)$order->total_amount . "&addInfo=" . urlencode($order->order_code) . "&accountName=" . urlencode("THAN THAI SON");
                            @endphp
                            <img src="{{ $detailVietQrUrl }}" alt="Mã VietQR MBBank" class="img-fluid rounded border shadow-sm" style="max-width: 220px; height: auto;">
                            <div class="text-muted small mt-2 fw-semibold">Quét mã bằng App Ngân Hàng</div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h5 class="fw-bold text-dark mb-2">Thanh toán qua chuyển khoản ngân hàng VietQR</h5>
                        <p class="text-muted small mb-3">Vui lòng quét mã QR hoặc chuyển khoản vào tài khoản bên dưới với nội dung là mã đơn hàng.</p>
                        <div class="row g-2 text-dark small mb-3">
                            <div class="col-sm-6">
                                <span class="text-muted">Ngân hàng:</span> <strong>MBBank (Quân Đội)</strong>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted">Chủ tài khoản:</span> <strong class="text-uppercase text-primary">THAN THAI SON</strong>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted">Số tài khoản:</span> <strong class="text-danger font-monospace fs-6">011116042004</strong>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted">Số tiền:</span> <strong class="text-success fs-6">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</strong>
                            </div>
                            <div class="col-12 border-top pt-2">
                                <span class="text-muted">Nội dung chuyển khoản:</span> 
                                <span class="badge bg-danger fs-6 font-monospace">{{ $order->order_code }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Nếu chọn MoMo mà chưa thanh toán, hiển thị nút chuyển sang cổng MoMo -->
        @if($order->payment_method === 'momo' && $order->payment_status === 'unpaid' && $order->status !== 'cancelled')
            <div class="p-4 rounded-4 mb-4 border" style="background-color: #fff5f8; border-color: #fbcfe8 !important;">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3 text-white d-flex align-items-center justify-content-center" style="background-color: #a50064; width: 56px; height: 56px;">
                            <i class="fa-solid fa-wallet fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Đơn hàng chưa hoàn tất thanh toán MoMo</h5>
                            <p class="text-muted small mb-0">Số tiền cần thanh toán: <strong class="text-danger fs-6">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</strong></p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('orders.momo.pay', $order->id) }}" class="btn text-white px-4 py-2 rounded-pill fw-bold shadow-sm" style="background-color: #a50064;">
                            <i class="fa-solid fa-credit-card me-1"></i> Thanh toán ngay qua MoMo
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Thông tin giao hàng & Phương thức thanh toán -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 h-100 border">
                    <h6 class="fw-bold text-dark mb-3">
                        <i class="fa-solid fa-location-dot text-danger me-2"></i>Thông tin người nhận
                    </h6>
                    <ul class="list-unstyled mb-0 small d-flex flex-column gap-2">
                        <li><span class="text-muted">Họ tên:</span> <strong>{{ $order->customer_name }}</strong></li>
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

            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 h-100 border">
                    <h6 class="fw-bold text-dark mb-3">
                        <i class="fa-solid fa-wallet text-primary me-2"></i>Phương thức thanh toán
                    </h6>
                    <ul class="list-unstyled mb-0 small d-flex flex-column gap-2">
                        <li><span class="text-muted">Hình thức:</span> <strong>{{ $order->payment_method_name }}</strong></li>
                        <li>
                            <span class="text-muted">Trạng thái:</span> 
                            <span class="badge {{ $order->payment_status_badge_class }} px-2 py-1">{{ $order->payment_status_label }}</span>
                        </li>
                        <li><span class="text-muted">Phí giao hàng:</span> <strong class="text-success">Miễn phí (0 VNĐ)</strong></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bảng danh sách sản phẩm trong đơn -->
        <h6 class="fw-bold text-dark mb-3">
            <i class="fa-solid fa-boxes-stacked text-primary me-2"></i>Chi tiết các mặt hàng
        </h6>
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">#</th>
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
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Tổng thanh toán:</td>
                        <td class="text-end fw-bold text-danger fs-5">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Các nút thao tác -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 border-top no-print">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Quay lại danh sách đơn
            </a>
            <div class="d-flex gap-2">
                @if($order->status === 'pending')
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng {{ $order->order_code }} không?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fa-solid fa-ban me-1"></i> Hủy đơn hàng
                        </button>
                    </form>
                @endif
                <button onclick="window.print()" class="btn btn-outline-primary">
                    <i class="fa-solid fa-print me-1"></i> In hóa đơn
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
