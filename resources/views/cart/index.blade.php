@extends('layouts.app')

@section('title', 'Giỏ Hàng Của Bạn - Thái Sơn Tiles')

@section('styles')
<style>
    .cart-select-bar {
        background-color: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 12px 20px;
    }
    .form-check-input {
        width: 1.25em;
        height: 1.25em;
        cursor: pointer;
    }
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .item-row.unselected {
        opacity: 0.6;
        background-color: #fafafa;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white p-3 rounded-4 border shadow-sm" style="border-color: var(--border-color) !important;">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none" style="color: var(--brown-600);"><i class="fa-solid fa-house me-1"></i> Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fa-solid fa-cart-shopping me-1"></i> Giỏ hàng</li>
        </ol>
    </nav>

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 fw-bold text-dark mb-0">
            <i class="fa-solid fa-cart-shopping me-2" style="color: var(--beige-accent);"></i>Giỏ hàng của bạn
        </h1>
        <a href="{{ route('welcome') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Tiếp tục mua sắm
        </a>
    </div>

    @if(count($cart) > 0)
        <!-- Form Thanh toán các sản phẩm được chọn -->
        <form action="{{ route('checkout.index') }}" method="GET" id="cartForm">
            <!-- Thanh Chọn tất cả trên đầu -->
            <div class="cart-select-bar d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3 shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check d-flex align-items-center gap-2 mb-0">
                        <input class="form-check-input" type="checkbox" id="selectAllCheckbox" checked onchange="toggleSelectAll(this.checked)">
                        <label class="form-check-label fw-bold text-dark cursor-pointer" for="selectAllCheckbox">
                            Chọn tất cả (<span id="selectedCountTop">{{ count($cart) }}</span>/{{ count($cart) }} sản phẩm)
                        </label>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">Tổng đã chọn:</span>
                    <strong class="text-danger fs-6" id="selectedTotalTop">0 VNĐ</strong>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: linear-gradient(135deg, #261810 0%, #3d281d 100%); color: #ffffff;">
                            <tr>
                                <th class="py-3 px-3 text-center text-white" style="width: 50px;">
                                    <span class="visually-hidden">Chọn</span>
                                </th>
                                <th class="py-3 px-4 text-white" style="min-width: 250px;">Tên sản phẩm</th>
                                <th class="py-3 px-3 text-center text-white" style="min-width: 170px;">Số lượng</th>
                                <th class="py-3 px-3 text-end text-white" style="min-width: 130px;">Giá</th>
                                <th class="py-3 px-3 text-end text-white" style="min-width: 140px;">Thành tiền</th>
                                <th class="py-3 px-3 text-white" style="min-width: 130px;">Danh mục</th>
                                <th class="py-3 px-4 text-center text-white" style="min-width: 90px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($cart as $id => $details)
                                @php 
                                    $subtotal = $details['price'] * $details['quantity'];
                                    $total += $subtotal; 
                                @endphp
                                <tr class="item-row" id="row_{{ $id }}">
                                    <!-- Checkbox chọn sản phẩm -->
                                    <td class="py-3 px-3 text-center">
                                        <input type="checkbox" name="selected_items[]" value="{{ $id }}" 
                                               class="form-check-input item-checkbox" 
                                               data-subtotal="{{ $subtotal }}" 
                                               data-id="{{ $id }}"
                                               checked 
                                               onchange="updateCartSelection()">
                                    </td>

                                    <!-- Tên sản phẩm, ảnh & màu sắc -->
                                    <td class="py-3 px-4">
                                        <div class="d-flex align-items-center gap-3">
                                            @if(!empty($details['thumbnail']))
                                                <img src="{{ asset($details['thumbnail']) }}" alt="{{ $details['name'] }}" class="rounded border object-fit-cover shadow-sm" style="width: 55px; height: 55px;">
                                            @else
                                                <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted" style="width: 55px; height: 55px;">
                                                    <i class="fa-solid fa-image"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('products.show', $details['product_id'] ?? $id) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                                                    {{ $details['name'] }}
                                                </a>
                                                @if(!empty($details['color_name']))
                                                    <div class="mt-1">
                                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size: 0.75rem;">
                                                            <i class="fa-solid fa-palette me-1"></i> Vân / Màu: {{ $details['color_name'] }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Số lượng & Đổi màu sắc -->
                                    <td class="py-3 px-3 text-center">
                                        <!-- Form cập nhật số lượng riêng biệt -->
                                        @php
                                            $prodId = $details['product_id'] ?? null;
                                            $currentProd = ($prodId && isset($products[$prodId])) ? $products[$prodId] : null;
                                        @endphp

                                        <div class="d-inline-flex flex-column align-items-center gap-2">
                                            @if($currentProd && $currentProd->variants->count() > 0)
                                                <select onchange="updateVariantItem('{{ $id }}', this.value)" class="form-select form-select-sm border-primary-subtle" style="font-size: 0.8rem; min-width: 130px;" title="Chọn đổi màu khác">
                                                    @foreach($currentProd->variants as $v)
                                                        <option value="{{ $v->id }}" {{ (isset($details['variant_id']) && $details['variant_id'] == $v->id) ? 'selected' : '' }}>
                                                            {{ $v->color_name }} (Kho: {{ $v->stock }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif

                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                <input type="number" id="qty_{{ $id }}" value="{{ $details['quantity'] }}" min="1" class="form-control form-control-sm text-center fw-bold" style="width: 60px;">
                                                <button type="button" onclick="updateQuantityItem('{{ $id }}')" class="btn btn-sm btn-outline-primary" title="Cập nhật số lượng">
                                                    <i class="fa-solid fa-rotate"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Giá đơn vị -->
                                    <td class="py-3 px-3 text-end text-secondary fw-semibold">
                                        {{ number_format($details['price'], 0, ',', '.') }} VNĐ
                                    </td>

                                    <!-- Thành tiền -->
                                    <td class="py-3 px-3 text-end fw-bold text-primary fs-6">
                                        {{ number_format($subtotal, 0, ',', '.') }} VNĐ
                                    </td>

                                    <!-- Danh mục -->
                                    <td class="py-3 px-3">
                                        <span class="badge bg-light text-dark border">{{ $details['category'] ?? 'Chưa phân loại' }}</span>
                                    </td>

                                    <!-- Hành động: Xoá -->
                                    <td class="py-3 px-4 text-center">
                                        <button type="button" onclick="deleteCartItem('{{ $id }}')" class="btn btn-sm btn-outline-danger" title="Xoá sản phẩm">
                                            <i class="fa-solid fa-trash me-1"></i> Xoá
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Footer bảng tổng kết đơn hàng -->
                <div class="card-footer bg-light p-4 border-top">
                    <div class="row align-items-center justify-content-between g-3">
                        <div class="col-md-5">
                            <a href="{{ route('welcome') }}" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-arrow-left me-1"></i> Tiếp tục mua sắm
                            </a>
                        </div>
                        <div class="col-md-7 text-md-end">
                            <div class="mb-3">
                                <span class="text-muted fs-6 me-2">Đã chọn: <strong id="selectedCountText" class="text-primary">{{ count($cart) }}</strong> sản phẩm | Tổng thanh toán:</span>
                                <span class="h3 fw-bold text-danger mb-0" id="selectedTotalText">{{ number_format($total, 0, ',', '.') }} VNĐ</span>
                            </div>
                            <div class="d-flex justify-content-md-end gap-2">
                                <button type="submit" id="btnCheckout" class="btn btn-primary btn-lg px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                                    <i class="fa-solid fa-credit-card"></i>
                                    <span>Tiến hành Đặt hàng & Thanh toán (<span id="selectedCountBtn">{{ count($cart) }}</span>)</span>
                                    <i class="fa-solid fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Hidden Helper Forms for Update and Delete -->
        <form id="globalUpdateForm" action="" method="POST" style="display: none;">
            @csrf
            @method('PATCH')
            <input type="hidden" name="quantity" id="globalQuantityInput">
            <input type="hidden" name="variant_id" id="globalVariantInput">
        </form>

        <form id="globalDeleteForm" action="" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @else
        <!-- Giỏ hàng trống -->
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4" style="border: 1px solid var(--border-color) !important;">
            <div class="my-4">
                <div class="d-inline-flex p-4 rounded-circle" style="background: rgba(224, 169, 109, 0.15); color: var(--beige-accent);">
                    <i class="fa-solid fa-cart-shopping" style="font-size: 3.5rem;"></i>
                </div>
            </div>
            <h4 class="fw-bold text-dark mb-2">Giỏ hàng của bạn đang trống</h4>
            <p class="text-muted mb-4" style="max-width: 450px; margin: 0 auto;">Khám phá bộ sưu tập các mẫu gạch lát nền, gạch ốp tường cao cấp và kiến tạo không gian sống mơ ước.</p>
            <div>
                <a href="{{ route('welcome') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-bold shadow-sm">
                    <i class="fa-solid fa-bag-shopping me-1"></i> Khám phá sản phẩm ngay
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    const totalItemsCount = {{ count($cart ?? []) }};

    function formatNumberVND(num) {
        return new Intl.NumberFormat('vi-VN').format(num) + ' VNĐ';
    }

    // Cập nhật tính toán khi tích/bỏ tích checkbox
    function updateCartSelection() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        let selectedCount = 0;
        let selectedTotal = 0;

        checkboxes.forEach(cb => {
            const row = document.getElementById('row_' + cb.dataset.id);
            if (cb.checked) {
                selectedCount++;
                selectedTotal += parseFloat(cb.dataset.subtotal || 0);
                if (row) row.classList.remove('unselected');
            } else {
                if (row) row.classList.add('unselected');
            }
        });

        // Cập nhật text hiển thị
        const formattedTotal = formatNumberVND(selectedTotal);
        document.getElementById('selectedCountTop').textContent = selectedCount;
        document.getElementById('selectedCountText').textContent = selectedCount;
        document.getElementById('selectedCountBtn').textContent = selectedCount;
        document.getElementById('selectedTotalTop').textContent = formattedTotal;
        document.getElementById('selectedTotalText').textContent = formattedTotal;

        // Cập nhật trạng thái checkbox Chọn tất cả
        const selectAllCb = document.getElementById('selectAllCheckbox');
        if (selectAllCb) {
            selectAllCb.checked = (selectedCount === checkboxes.length && checkboxes.length > 0);
            selectAllCb.indeterminate = (selectedCount > 0 && selectedCount < checkboxes.length);
        }

        // Bật/tắt nút thanh toán
        const btnCheckout = document.getElementById('btnCheckout');
        if (btnCheckout) {
            if (selectedCount === 0) {
                btnCheckout.disabled = true;
                btnCheckout.classList.add('opacity-50');
            } else {
                btnCheckout.disabled = false;
                btnCheckout.classList.remove('opacity-50');
            }
        }
    }

    // Chọn tất cả hoặc bỏ chọn tất cả
    function toggleSelectAll(checked) {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = checked;
        });
        updateCartSelection();
    }

    // Cập nhật số lượng sản phẩm
    function updateQuantityItem(itemId) {
        const qtyInput = document.getElementById('qty_' + itemId);
        const qty = qtyInput ? qtyInput.value : 1;
        const form = document.getElementById('globalUpdateForm');
        form.action = "{{ url('/cart') }}/" + itemId;
        document.getElementById('globalQuantityInput').value = qty;
        form.submit();
    }

    // Đổi màu sắc biến thể
    function updateVariantItem(itemId, variantId) {
        const qtyInput = document.getElementById('qty_' + itemId);
        const qty = qtyInput ? qtyInput.value : 1;
        const form = document.getElementById('globalUpdateForm');
        form.action = "{{ url('/cart') }}/" + itemId;
        document.getElementById('globalQuantityInput').value = qty;
        document.getElementById('globalVariantInput').value = variantId;
        form.submit();
    }

    // Xóa sản phẩm khỏi giỏ
    function deleteCartItem(itemId) {
        if (confirm('Bạn có chắc chắn muốn xoá sản phẩm này khỏi giỏ hàng?')) {
            const form = document.getElementById('globalDeleteForm');
            form.action = "{{ url('/cart/remove') }}/" + itemId;
            form.submit();
        }
    }

    // Khởi tạo tính toán ban đầu
    document.addEventListener('DOMContentLoaded', function () {
        updateCartSelection();
    });
</script>
@endsection
