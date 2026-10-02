@extends('layouts.app')

@section('title', 'Thanh Toán Đơn Hàng - Thái Sơn Tiles')

@section('styles')
<style>
    .checkout-step-badge {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: var(--primary-color);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }
    .payment-option-card {
        border: 2px solid var(--border-color);
        border-radius: 14px;
        padding: 16px 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fff;
    }
    .payment-option-card:hover {
        border-color: var(--primary-color);
        background-color: #f8fafc;
    }
    .payment-option-card.active {
        border-color: var(--primary-color);
        background-color: #f0f9ff;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.12);
    }
    .order-summary-card {
        position: sticky;
        top: 90px;
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
</style>
@endsection

@section('content')
<div class="container py-3">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white p-3 rounded-3 border shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none text-primary"><i class="fa-solid fa-house me-1"></i> Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none text-primary"><i class="fa-solid fa-cart-shopping me-1"></i> Giỏ hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fa-solid fa-credit-card me-1"></i> Thanh toán đơn hàng</li>
        </ol>
    </nav>

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="fa-solid fa-cash-register text-primary me-2"></i>Thanh toán đơn hàng
            </h1>
            <p class="text-muted mb-0 small">Vui lòng kiểm tra địa chỉ nhận hàng và lựa chọn phương thức thanh toán phù hợp.</p>
        </div>
        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại giỏ hàng
        </a>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        @if(isset($selectedItemKeys) && is_array($selectedItemKeys))
            @foreach($selectedItemKeys as $sKey)
                <input type="hidden" name="selected_items[]" value="{{ $sKey }}">
            @endforeach
        @endif
        <div class="row g-4">
            <!-- Cột Trái: Thông tin giao hàng & Phương thức thanh toán -->
            <div class="col-lg-7">
                <!-- 1. Thông tin giao hàng -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3 pb-2 border-bottom">
                        <span class="checkout-step-badge">1</span>
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="fa-solid fa-location-dot text-danger me-2"></i>Thông tin giao hàng
                        </h5>
                    </div>

                    <div class="row g-3">
                        <!-- Họ và tên -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Họ và tên người nhận <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" 
                                   value="{{ old('customer_name', $user->name ?? '') }}" placeholder="Ví dụ: Trần Văn Nam" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Số điện thoại -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Số điện thoại liên hệ <span class="text-danger">*</span></label>
                            <input type="tel" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" 
                                   value="{{ old('customer_phone') }}" placeholder="Ví dụ: 0912345678" required>
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Địa chỉ Email (Nhận thông báo đơn hàng)</label>
                            <input type="email" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror" 
                                   value="{{ old('customer_email', $user->email ?? '') }}" placeholder="Ví dụ: khachhang@gmail.com">
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tỉnh / Thành phố -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                            <select id="province_select" class="form-select @error('province') is-invalid @enderror" required>
                                <option value="">-- Đang tải Tỉnh/Thành... --</option>
                            </select>
                            <input type="hidden" name="province" id="province_name" value="{{ old('province', '') }}">
                            @error('province')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Quận / Huyện -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Quận / Huyện <span class="text-danger">*</span></label>
                            <select id="district_select" class="form-select @error('district') is-invalid @enderror" disabled required>
                                <option value="">-- Chọn Quận/Huyện --</option>
                            </select>
                            <input type="hidden" name="district" id="district_name" value="{{ old('district', '') }}">
                            <input type="hidden" name="to_district_id" id="to_district_id" value="{{ old('to_district_id', '') }}">
                            @error('district')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phường / Xã -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Phường / Xã <span class="text-danger">*</span></label>
                            <select id="ward_select" class="form-select @error('ward') is-invalid @enderror" disabled required>
                                <option value="">-- Chọn Phường/Xã --</option>
                            </select>
                            <input type="hidden" name="ward" id="ward_name" value="{{ old('ward', '') }}">
                            <input type="hidden" name="to_ward_code" id="to_ward_code" value="{{ old('to_ward_code', '') }}">
                            @error('ward')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Địa chỉ chi tiết -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Địa chỉ chi tiết (Số nhà, tên đường, tòa nhà) <span class="text-danger">*</span></label>
                            <input type="text" name="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" 
                                   value="{{ old('shipping_address') }}" placeholder="Ví dụ: Số 20, Ngõ 15 Nguyễn Trãi..." required>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ghi chú -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Ghi chú giao hàng (Tùy chọn)</label>
                            <textarea name="notes" rows="2" class="form-control" placeholder="Ví dụ: Giao giờ hành chính, bốc xếp lên tầng 2...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Phương thức thanh toán -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3 pb-2 border-bottom">
                        <span class="checkout-step-badge">2</span>
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="fa-solid fa-wallet text-primary me-2"></i>Phương thức thanh toán
                        </h5>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <!-- Lựa chọn 1: Chuyển khoản QR Ngân hàng (VietQR) -->
                        <label class="payment-option-card active d-flex align-items-start gap-3" id="labelBanking">
                            <input class="form-check-input mt-1" type="radio" name="payment_method" id="payBanking" value="banking" checked onchange="handlePaymentChange(this.value)">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="fw-bold text-dark fs-6">
                                        <i class="fa-solid fa-qrcode text-primary me-2"></i>Chuyển khoản Ngân hàng (Mã QR VietQR)
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">Khuyên dùng</span>
                                </div>
                                <p class="text-muted small mb-2 mt-1">
                                    Quét mã QR qua ứng dụng ngân hàng bất kỳ (MBBank, Vietcombank, Techcombank, Momo...). Chuyển khoản 24/7 tức thì.
                                </p>
                                
                                <!-- Box thông tin tài khoản MBBank -->
                                <div class="p-3 rounded-3 bg-light border border-primary-subtle" id="bankInfoBox">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-danger fw-bold px-2 py-1">MB BANK</span>
                                            <span class="fw-bold text-dark small">Ngân Hàng TMCP Quân Đội</span>
                                        </div>
                                        <span class="badge bg-success-subtle text-success small"><i class="fa-solid fa-bolt me-1"></i>Xử lý tự động</span>
                                    </div>
                                    <div class="row g-2 text-dark small">
                                        <div class="col-sm-6">
                                            <span class="text-muted">Chủ tài khoản:</span>
                                            <strong class="text-uppercase ms-1 text-primary">THAN THAI SON</strong>
                                        </div>
                                        <div class="col-sm-6">
                                            <span class="text-muted">Số tài khoản:</span>
                                            <strong class="font-monospace ms-1 fs-6 text-danger">011116042004</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Lựa chọn 2: Thanh toán khi nhận hàng (COD) -->
                        <label class="payment-option-card d-flex align-items-start gap-3" id="labelCod">
                            <input class="form-check-input mt-1" type="radio" name="payment_method" id="payCod" value="cod" onchange="handlePaymentChange(this.value)">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="fw-bold text-dark fs-6">
                                        <i class="fa-solid fa-truck-ramp-box text-success me-2"></i>Thanh toán khi nhận hàng (COD)
                                    </div>
                                </div>
                                <p class="text-muted small mb-0 mt-1">
                                    Nhận hàng, kiểm tra sản phẩm gạch ốp lát và thanh toán tiền mặt trực tiếp cho nhân viên giao hàng.
                                </p>
                            </div>
                        </label>

                        <!-- Lựa chọn 3: Thanh toán qua Cổng MoMo (Sandbox) -->
                        <label class="payment-option-card d-flex align-items-start gap-3" id="labelMomo">
                            <input class="form-check-input mt-1" type="radio" name="payment_method" id="payMomo" value="momo" onchange="handlePaymentChange(this.value)">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="fw-bold text-dark fs-6">
                                        <span class="badge text-white me-2 px-2 py-1" style="background-color: #a50064; font-size: 0.85rem;">MoMo</span>Cổng thanh toán Ví MoMo (ATM / QR Code)
                                    </div>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Sandbox Test</span>
                                </div>
                                <p class="text-muted small mb-0 mt-1">
                                    Chuyển hướng an toàn sang cổng thanh toán MoMo. Hỗ trợ quét mã QR MoMo và thẻ ATM ngân hàng nội địa thử nghiệm.
                                </p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Cột Phải: Chi tiết giỏ hàng & Tổng thanh toán -->
            <div class="col-lg-5">
                <div class="order-summary-card p-4">
                    <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="fa-solid fa-receipt text-primary me-2"></i>Chi tiết đơn hàng
                        </h5>
                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1 fw-bold">
                            {{ $totalQuantity }} sản phẩm
                        </span>
                    </div>

                    <!-- Danh sách sản phẩm -->
                    <div class="overflow-y-auto mb-3 pe-1" style="max-height: 320px;">
                        @foreach($cart as $item)
                            @php $itemTotal = $item['price'] * $item['quantity']; @endphp
                            <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                                @if(!empty($item['thumbnail']))
                                    <img src="{{ asset($item['thumbnail']) }}" alt="{{ $item['name'] }}" 
                                         class="rounded-3 border shadow-sm object-fit-cover flex-shrink-0" style="width: 52px; height: 52px;">
                                @else
                                    <div class="rounded-3 border bg-light d-flex align-items-center justify-content-center text-muted flex-shrink-0" style="width: 52px; height: 52px;">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif

                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-bold text-dark text-truncate small" title="{{ $item['name'] }}">
                                        {{ $item['name'] }}
                                    </div>
                                    @if(!empty($item['color_name']))
                                        <div class="small text-muted">
                                            <i class="fa-solid fa-palette me-1 text-primary"></i>Màu: <strong>{{ $item['color_name'] }}</strong>
                                        </div>
                                    @endif
                                    <div class="small text-muted mt-1">
                                        {{ number_format($item['price'], 0, ',', '.') }} đ × <strong class="text-dark">{{ $item['quantity'] }}</strong>
                                    </div>
                                </div>

                                <div class="text-end flex-shrink-0 fw-bold text-primary small">
                                    {{ number_format($itemTotal, 0, ',', '.') }} đ
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Tóm tắt chi phí -->
                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span>Tiền hàng:</span>
                        <span class="fw-semibold text-dark">{{ number_format($subtotal, 0, ',', '.') }} VNĐ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 small text-muted">
                        <span>Phí vận chuyển (GHN):</span>
                        <span id="shipping_fee_text" class="text-primary fw-bold">0 VNĐ</span>
                    </div>

                    <!-- Input ẩn tính toán -->
                    <input type="hidden" name="shipping_fee" id="shipping_fee_input" value="0">
                    <input type="hidden" id="subtotal_input" value="{{ $subtotal }}">
                    <input type="hidden" id="total_price_input" value="{{ $subtotal }}">

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fs-6 fw-bold text-dark">Tổng thanh toán:</span>
                        <span id="final_total_text" class="h4 fw-bold text-danger mb-0">
                            {{ number_format($subtotal, 0, ',', '.') }} VNĐ
                        </span>
                    </div>

                    <!-- Nút Đặt Hàng -->
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold fs-6 shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-shield-check"></i>
                        <span>XÁC NHẬN ĐẶT HÀNG NGAY</span>
                    </button>

                    <div class="text-center mt-3 text-muted small">
                        <i class="fa-solid fa-lock text-success me-1"></i> Thông tin thanh toán được bảo mật an toàn 100%
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function handlePaymentChange(val) {
        const labelBanking = document.getElementById('labelBanking');
        const labelCod = document.getElementById('labelCod');
        const labelMomo = document.getElementById('labelMomo');
        const bankInfoBox = document.getElementById('bankInfoBox');
        
        [labelBanking, labelCod, labelMomo].forEach(el => el && el.classList.remove('active'));

        if (val === 'banking') {
            if (labelBanking) labelBanking.classList.add('active');
            if (bankInfoBox) bankInfoBox.style.display = 'block';
        } else if (val === 'momo') {
            if (labelMomo) labelMomo.classList.add('active');
            if (bankInfoBox) bankInfoBox.style.display = 'none';
        } else {
            if (labelCod) labelCod.classList.add('active');
            if (bankInfoBox) bankInfoBox.style.display = 'none';
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        const provinceSelect = document.getElementById('province_select');
        const districtSelect = document.getElementById('district_select');
        const wardSelect = document.getElementById('ward_select');

        const provinceNameInput = document.getElementById('province_name');
        const districtNameInput = document.getElementById('district_name');
        const wardNameInput = document.getElementById('ward_name');
        const toDistrictIdInput = document.getElementById('to_district_id');
        const toWardCodeInput = document.getElementById('to_ward_code');

        const shippingFeeText = document.getElementById('shipping_fee_text');
        const shippingFeeInput = document.getElementById('shipping_fee_input');
        const finalTotalText = document.getElementById('final_total_text');
        const subtotalInput = document.getElementById('subtotal_input');

        const districtsUrl = "{{ route('locations.districts', ['provinceId' => '__PROVINCE__']) }}";
        const wardsUrl = "{{ route('locations.wards', ['districtId' => '__DISTRICT__']) }}";

        const subtotal = parseInt(subtotalInput ? subtotalInput.value : 0) || 0;

        function updateTotals(fee) {
            shippingFeeText.innerText = new Intl.NumberFormat('vi-VN').format(fee) + ' VNĐ';
            if (shippingFeeInput) shippingFeeInput.value = fee;
            const finalAmount = subtotal + fee;
            finalTotalText.innerText = new Intl.NumberFormat('vi-VN').format(finalAmount) + ' VNĐ';
        }

        // 1. Tải danh sách Tỉnh/Thành từ GHN
        fetch("{{ route('locations.provinces') }}")
            .then(res => res.json())
            .then(res => {
                if (res && res.data && Array.isArray(res.data)) {
                    let options = '<option value="">-- Chọn Tỉnh/Thành --</option>';
                    res.data.forEach(p => {
                        options += `<option value="${p.ProvinceID}" data-name="${p.ProvinceName}">${p.ProvinceName}</option>`;
                    });
                    provinceSelect.innerHTML = options;
                } else {
                    provinceSelect.innerHTML = '<option value="">-- Không tải được tỉnh/thành --</option>';
                }
            })
            .catch(err => {
                console.error("Lỗi load tỉnh thành:", err);
                provinceSelect.innerHTML = '<option value="">-- Lỗi kết nối GHN --</option>';
            });

        // 2. Khi chọn Tỉnh -> Tải Quận/Huyện
        provinceSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            provinceNameInput.value = selectedOption ? (selectedOption.getAttribute('data-name') || '') : '';

            districtSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
            districtSelect.disabled = true;
            wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
            wardSelect.disabled = true;

            districtNameInput.value = '';
            toDistrictIdInput.value = '';
            wardNameInput.value = '';
            toWardCodeInput.value = '';

            updateTotals(0);

            if (!this.value) return;

            fetch(districtsUrl.replace('__PROVINCE__', this.value))
                .then(res => res.json())
                .then(res => {
                    if (res && res.data && Array.isArray(res.data)) {
                        let options = '<option value="">-- Chọn Quận/Huyện --</option>';
                        res.data.forEach(d => {
                            options += `<option value="${d.DistrictID}" data-name="${d.DistrictName}">${d.DistrictName}</option>`;
                        });
                        districtSelect.innerHTML = options;
                        districtSelect.disabled = false;
                    } else {
                        districtSelect.innerHTML = '<option value="">-- Không tải được quận/huyện --</option>';
                    }
                })
                .catch(err => {
                    console.error("Lỗi load quận huyện:", err);
                    districtSelect.innerHTML = '<option value="">-- Lỗi kết nối GHN --</option>';
                });
        });

        // 3. Khi chọn Quận/Huyện -> Tải Phường/Xã
        districtSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            districtNameInput.value = selectedOption ? (selectedOption.getAttribute('data-name') || '') : '';
            toDistrictIdInput.value = this.value;

            wardSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
            wardSelect.disabled = true;

            wardNameInput.value = '';
            toWardCodeInput.value = '';

            updateTotals(0);

            if (!this.value) return;

            fetch(wardsUrl.replace('__DISTRICT__', this.value))
                .then(res => res.json())
                .then(res => {
                    if (res && res.data && Array.isArray(res.data)) {
                        let options = '<option value="">-- Chọn Phường/Xã --</option>';
                        res.data.forEach(w => {
                            options += `<option value="${w.WardCode}" data-name="${w.WardName}">${w.WardName}</option>`;
                        });
                        wardSelect.innerHTML = options;
                        wardSelect.disabled = false;
                    } else {
                        wardSelect.innerHTML = '<option value="">-- Không tải được phường/xã --</option>';
                    }
                })
                .catch(err => {
                    console.error("Lỗi load phường xã:", err);
                    wardSelect.innerHTML = '<option value="">-- Lỗi kết nối GHN --</option>';
                });
        });

        // 4. Khi chọn Phường/Xã -> Tính cước vận chuyển GHN
        wardSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            wardNameInput.value = selectedOption ? (selectedOption.getAttribute('data-name') || '') : '';
            toWardCodeInput.value = this.value;

            if (!this.value || !districtSelect.value) return;

            shippingFeeText.innerText = 'Đang tính cước...';

            fetch("{{ route('locations.fee') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    to_district_id: parseInt(districtSelect.value),
                    to_ward_code: this.value
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res && (res.code === 200 || res.code === 0) && res.data) {
                    const fee = parseInt(res.data.total) || 0;
                    updateTotals(fee);
                } else {
                    shippingFeeText.innerText = 'Chưa hỗ trợ (0 VNĐ)';
                    updateTotals(0);
                }
            })
            .catch(err => {
                console.error("Lỗi tính phí:", err);
                shippingFeeText.innerText = 'Lỗi tính phí';
                updateTotals(0);
            });
        });
    });
</script>
@endsection
