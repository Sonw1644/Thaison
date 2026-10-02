@extends('layouts.app')

@section('title', $product->name . ' - Chi Tiết Sản Phẩm')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb bg-white p-3 rounded-3 border shadow-sm">
        <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none text-primary"><i class="fa-solid fa-house me-1"></i> Trang chủ</a></li>
        @if($product->category)
            <li class="breadcrumb-item text-muted">{{ $product->category->name }}</li>
        @endif
        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
    </ol>
</nav>

<div class="row g-4 mb-5">
    <!-- Image & Gallery -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-center">
            @if ($product->thumbnail)
                <img id="mainProductImage" src="{{ $product->thumbnail }}" alt="{{ $product->name }}" class="img-fluid rounded-3 shadow-sm w-100" style="max-height: 420px; object-fit: cover; transition: opacity 0.25s ease-in-out;">
            @else
                <img id="mainProductImage" src="" alt="{{ $product->name }}" class="img-fluid rounded-3 shadow-sm w-100 d-none" style="max-height: 420px; object-fit: cover; transition: opacity 0.25s ease-in-out;">
                <div id="noImagePlaceholder" class="bg-light rounded-3 d-flex flex-column align-items-center justify-content-center py-5 text-muted" style="min-height: 350px;">
                    <i class="fa-regular fa-image fs-1 mb-2"></i>
                    <span>Chưa có hình ảnh sản phẩm</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Product Info -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                <h1 class="h3 fw-bold text-dark mb-0">{{ $product->name }}</h1>
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-warning btn-sm fw-semibold">
                            <i class="fa-solid fa-pen-to-square me-1"></i> Chỉnh sửa (Admin)
                        </a>
                    @endif
                @endauth
            </div>

            <div class="d-flex flex-wrap align-items-center gap-3 my-3">
                <span class="fs-3 fw-bold" style="color: #a0522d;">
                    {{ number_format($product->price, 0, ',', '.') }} VNĐ
                </span>
                <span class="text-muted">/ {{ $product->unit ?? 'Thùng' }}</span>
                @if($product->stock > 0)
                    <span id="productStockBadge" class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill">
                        <i class="fa-solid fa-check me-1"></i> Còn hàng ({{ $product->stock }})
                    </span>
                @else
                    <span id="productStockBadge" class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill">
                        <i class="fa-solid fa-xmark me-1"></i> Hết hàng
                    </span>
                @endif
            </div>

            <p class="text-secondary mb-4">
                {{ $product->description ?? $product->short_description ?? 'Sản phẩm gạch ốp lát cao cấp đạt tiêu chuẩn chất lượng cao, bề mặt tinh tế, độ bền vượt trội.' }}
            </p>

            <!-- Specifications Table -->
            <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                <i class="fa-solid fa-circle-info text-primary me-2"></i>Chi tiết sản phẩm
            </h5>
            <div class="row g-2 mb-4">
                <div class="col-sm-6">
                    <div class="p-2 bg-light rounded border small">
                        <strong>Mã SKU:</strong> {{ $product->sku ?? 'N/A' }}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-2 bg-light rounded border small">
                        <strong>Danh mục:</strong> {{ optional($product->category)->name ?? 'Chưa phân loại' }}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-2 bg-light rounded border small">
                        <strong>Thương hiệu:</strong> {{ $product->brand ?? 'Chính hãng Thái Sơn' }}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-2 bg-light rounded border small">
                        <strong>Kích thước:</strong> {{ $product->size ?? 'Tiêu chuẩn' }}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-2 bg-light rounded border small">
                        <strong>Bề mặt:</strong> {{ $product->surface ?? 'Men matt / bóng' }}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-2 bg-light rounded border small">
                        <strong>Chất liệu:</strong> {{ $product->material ?? 'Porcelain / Granite' }}
                    </div>
                </div>
            </div>

            <!-- Variants if any -->
            <!-- Variants if any -->
            @if ($product->variants && $product->variants->count() > 0)
                <div class="mb-4">
                    <label class="fw-bold text-dark mb-2 d-block">Chọn loại màu sắc / vân gạch:</label>
                    <div class="d-flex flex-wrap gap-2" id="variantSelector">
                        @foreach ($product->variants as $index => $variant)
                            @php
                                $variantImg = $variant->image ? asset($variant->image) : ($product->thumbnail ? asset($product->thumbnail) : '');
                            @endphp
                            <label class="variant-option border rounded-3 p-2 d-flex align-items-center gap-2 cursor-pointer bg-light {{ $index === 0 ? 'border-primary bg-primary-subtle shadow-sm active' : '' }}" 
                                   data-image="{{ $variantImg }}"
                                   data-stock="{{ $variant->stock }}"
                                   data-name="{{ $variant->color_name }}"
                                   style="cursor: pointer; transition: all 0.2s;">
                                <input type="radio" name="variant_id" form="addToCartForm" value="{{ $variant->id }}" {{ $index === 0 ? 'checked' : '' }} class="d-none variant-radio">
                                <span class="rounded-circle border shadow-sm" style="display:inline-block; width:18px; height:18px; background-color: {{ $variant->color_code ?? '#ccc' }};"></span>
                                <span class="fw-semibold small text-dark">{{ $variant->color_name }}</span>
                                <span class="badge bg-secondary rounded-pill" style="font-size: 0.7rem;">Kho: {{ $variant->stock }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Add to Cart Action -->
            <div class="my-4 p-3 bg-light rounded-3 border">
                @auth
                    <!-- Form để thêm sản phẩm vào giỏ hàng -->
                    <form id="addToCartForm" action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        @if ($product->variants && $product->variants->count() > 0)
                            <input type="hidden" name="variant_id" id="selectedVariantId" value="{{ $product->variants->first()->id }}">
                        @endif
                        <div class="row align-items-center g-3">
                            <div class="col-auto">
                                <label for="quantityInput" class="fw-bold text-dark">Số lượng ({{ $product->unit ?? 'Thùng' }}):</label>
                            </div>
                            <div class="col-auto">
                                <div class="input-group" style="width: 130px;">
                                    <button class="btn btn-outline-secondary" type="button" onclick="const q = document.getElementById('quantityInput'); if(parseInt(q.value) > 1) q.value = parseInt(q.value) - 1;">-</button>
                                    <input type="number" id="quantityInput" name="quantity" value="1" min="1" max="{{ $product->stock > 0 ? $product->stock : 1 }}" class="form-control text-center fw-bold" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="const q = document.getElementById('quantityInput'); q.value = parseInt(q.value) + 1;">+</button>
                                </div>
                            </div>
                            <div class="col-auto d-flex flex-wrap gap-2">
                                <button type="submit" name="action" value="add_to_cart" class="btn btn-outline-primary px-3 py-2 fw-semibold" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-cart-plus me-1"></i>Thêm vào giỏ
                                </button>
                                <button type="submit" name="action" value="buy_now" class="btn btn-danger px-4 py-2 fw-bold shadow-sm" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-bolt me-1"></i>Mua ngay
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="d-flex align-items-center gap-2 text-muted">
                        <i class="fa-solid fa-circle-info text-primary"></i>
                        <span>Vui lòng <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">đăng nhập</a> để thêm sản phẩm vào giỏ hàng và đặt mua.</span>
                    </div>
                @endauth
            </div>

            <!-- Navigation Actions -->
            <div class="d-flex flex-wrap gap-2 pt-3 border-top">
                <a href="{{ route('welcome') }}" class="btn btn-outline-secondary px-4 py-2">
                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại cửa hàng
                </a>
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary px-4 py-2">
                            <i class="fa-solid fa-table-list me-1"></i> Danh sách quản lý Admin
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.variant-option').forEach(option => {
        option.addEventListener('click', function() {
            // 1. Chuyển đổi trạng thái nút active
            document.querySelectorAll('.variant-option').forEach(opt => {
                opt.classList.remove('border-primary', 'bg-primary-subtle', 'shadow-sm', 'active');
                opt.classList.add('bg-light');
            });
            this.classList.remove('bg-light');
            this.classList.add('border-primary', 'bg-primary-subtle', 'shadow-sm', 'active');
            
            // 2. Chọn radio và cập nhật input hidden
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                const hiddenInput = document.getElementById('selectedVariantId');
                if (hiddenInput) hiddenInput.value = radio.value;
            }

            // 3. Đổi ảnh chính sang hình ảnh của màu đã chọn
            const newImage = this.getAttribute('data-image');
            const mainImg = document.getElementById('mainProductImage');
            const placeholder = document.getElementById('noImagePlaceholder');

            if (newImage && mainImg) {
                mainImg.style.opacity = '0.3';
                setTimeout(() => {
                    mainImg.src = newImage;
                    mainImg.classList.remove('d-none');
                    if (placeholder) placeholder.classList.add('d-none');
                    mainImg.style.opacity = '1';
                }, 150);
            }

            // 4. Cập nhật số lượng tồn kho hiển thị theo biến thể màu
            const stock = this.getAttribute('data-stock');
            const stockBadge = document.getElementById('productStockBadge');
            const quantityInput = document.getElementById('quantityInput');
            if (stock !== null && stockBadge) {
                const stockNum = parseInt(stock);
                if (stockNum > 0) {
                    stockBadge.className = 'badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill';
                    stockBadge.innerHTML = `<i class="fa-solid fa-check me-1"></i> Còn hàng (${stockNum})`;
                    if (quantityInput) quantityInput.max = stockNum;
                } else {
                    stockBadge.className = 'badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill';
                    stockBadge.innerHTML = `<i class="fa-solid fa-xmark me-1"></i> Hết hàng`;
                }
            }
        });
    });
</script>
@endsection
