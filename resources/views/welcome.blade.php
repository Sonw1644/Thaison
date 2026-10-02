@extends('layouts.app')

@section('title', 'SOL TILES - Kho Gạch Ốp Lát & Thiết Bị Cao Cấp')

@section('styles')
<style>
    .hero-banner {
        background: linear-gradient(135deg, #1f140e 0%, #2f1e16 50%, #432a1e 100%);
        border: 1px solid #5a3b2b;
        position: relative;
        overflow: hidden;
    }
    .hero-ambient-glow {
        position: absolute;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(229, 178, 121, 0.25) 0%, rgba(229, 178, 121, 0) 70%);
        top: -80px;
        right: -80px;
        filter: blur(60px);
        pointer-events: none;
    }
    .filter-sidebar-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #ebdcd0;
        box-shadow: 0 4px 20px rgba(38, 24, 16, 0.05);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .filter-section-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #2a1c15;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .filter-btn-toggle {
        background: #fdfaf6;
        border: 1.5px solid #d5c3b2;
        color: #4a3325;
        font-weight: 600;
        border-radius: 12px;
        padding: 8px 16px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s ease;
    }
    .filter-btn-toggle:hover {
        background: #3d281d;
        border-color: #3d281d;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(61, 40, 29, 0.2);
    }
    .cat-item-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 7px 12px;
        border-radius: 8px;
        color: #5c4335;
        font-size: 0.86rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-bottom: 2px;
    }
    .cat-item-link:hover, .cat-item-link.active {
        background: linear-gradient(135deg, #3d281d 0%, #5a3b2b 100%);
        color: #ffffff;
        font-weight: 600;
    }
    .cat-item-link .badge-num {
        font-size: 0.72rem;
        background: #f3ece4;
        color: #6d5141;
        padding: 2px 7px;
        border-radius: 10px;
    }
    .cat-item-link.active .badge-num {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }
    .product-card {
        border-radius: 16px;
        border: 1px solid #ede4db;
        background: #ffffff;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
    }
    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(38, 24, 16, 0.12);
        border-color: #c48b52;
    }
    .product-img-wrap {
        height: 220px;
        background-color: #f7f3ee;
        position: relative;
        overflow: hidden;
    }
    .product-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .product-card:hover .product-img-wrap img {
        transform: scale(1.08);
    }
    .spec-tag {
        font-size: 0.73rem;
        padding: 3px 8px;
        border-radius: 6px;
        background-color: #f4efe9;
        color: #634b3d;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .active-filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        background-color: #f5ede4;
        border: 1px solid #dfcfc0;
        border-radius: 20px;
        font-size: 0.8rem;
        color: #4a3325;
        font-weight: 600;
    }
    .active-filter-badge a {
        color: #a0522d;
        text-decoration: none;
    }
    .active-filter-badge a:hover {
        color: #dc2626;
    }
    .price-preset-btn {
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 6px;
        border: 1px solid #e0d2c5;
        background: #fdfbf7;
        color: #634b3d;
        cursor: pointer;
        transition: all 0.2s;
    }
    .price-preset-btn:hover {
        background: #3d281d;
        color: #fff;
        border-color: #3d281d;
    }
</style>
@endsection

@section('content')
<!-- Hero / Header Section Nâu Be -->
<div class="hero-banner rounded-4 shadow-sm p-4 p-md-5 mb-4 text-white animate-fade-in">
    <div class="hero-ambient-glow"></div>

    <div class="row align-items-center position-relative z-1 g-4">
        <div class="col-lg-7">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3" 
                 style="background: rgba(224, 169, 109, 0.18); border: 1px solid rgba(224, 169, 109, 0.4); color: #e0a96d; font-size: 0.85rem; font-weight: 600;">
                <i class="fa-solid fa-shapes"></i> THÁI SƠN CERAMIC TILES & SANITARY
            </div>
            <h1 class="display-6 fw-bold mb-3 text-white" style="letter-spacing: -0.5px; line-height: 1.25;">
                Kho Gạch Ốp Lát & Thiết Bị Kiến Trúc Cao Cấp
            </h1>
            <p class="text-light text-opacity-75 mb-4 lead fs-6" style="max-width: 580px; line-height: 1.6;">
                Khám phá bộ sưu tập gạch lát nền Granite, Porcelain khổ lớn, gạch ốp tường men bóng, gạch bông cổ điển & gạch giả gỗ chống thấm loại 1.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="#catalogSection" class="btn btn-warning fw-bold px-4 py-2 rounded-pill shadow-sm text-dark" style="background: #e0a96d; border-color: #e0a96d;">
                    <i class="fa-solid fa-compass me-1"></i> Khám Phá Sản Phẩm
                </a>
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light fw-bold px-4 py-2 rounded-pill shadow-sm">
                            <i class="fa-solid fa-gauge-high me-1 text-warning"></i> Quản trị Admin
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        <div class="col-lg-5">
            <div class="row g-3">
                <div class="col-12">
                    <div class="p-3 rounded-3 d-flex align-items-center gap-3" style="background: rgba(255, 255, 255, 0.07); border: 1px solid rgba(224, 169, 109, 0.25); backdrop-filter: blur(8px);">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(224, 169, 109, 0.2); color: #e0a96d;">
                            <i class="fa-solid fa-truck-fast fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-white small">Giao Hàng Tận Chân Công Trình</div>
                            <div class="text-light text-opacity-75" style="font-size: 0.78rem;">Vận chuyển toàn quốc, kiểm tra trước khi nhận</div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="p-3 rounded-3 d-flex align-items-center gap-3" style="background: rgba(255, 255, 255, 0.07); border: 1px solid rgba(224, 169, 109, 0.25); backdrop-filter: blur(8px);">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(224, 169, 109, 0.2); color: #e0a96d;">
                            <i class="fa-solid fa-certificate fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-white small">Cam Kết Chuẩn Chất Lượng Loại 1</div>
                            <div class="text-light text-opacity-75" style="font-size: 0.78rem;">Men xương đá Granite/Porcelain chống trầy tuyệt đối</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Catalog Layout with Left Collapsible Filter Sidebar -->
<div class="row g-4 position-relative" id="catalogSection">
    
    <!-- BỘ LỌC BÊN TRÁI (Left Filter Sidebar) -->
    <div class="col-lg-4 col-xl-3" id="filterSidebarCol">
        <div class="filter-sidebar-card sticky-top" style="top: 90px; z-index: 10;">
            <!-- Header Bộ Lọc Bên Trái -->
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between" style="border-color: #ebdcd0 !important;">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(224, 169, 109, 0.2); color: #8b5e3c;">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark fs-6">Bộ Lọc Tìm Kiếm</div>
                        <div class="text-muted" style="font-size: 0.75rem;">
                            {{ $products->total() }} sản phẩm phù hợp
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-1">
                    @if($activeFiltersCount > 0)
                        <a href="{{ route('welcome') }}" class="btn btn-sm btn-link text-danger text-decoration-none p-1" title="Đặt lại bộ lọc" style="font-size: 0.78rem;">
                            <i class="fa-solid fa-rotate-left"></i> Xóa lọc
                        </a>
                    @endif
                    <!-- Nút Thu Gọn Sang Trái -->
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle" id="btnToggleSidebar" title="Thu gọn / Đóng bộ lọc sang trái" style="width: 30px; height: 30px; padding: 0;">
                        <i class="fa-solid fa-chevron-left" id="sidebarChevronIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Form Tìm kiếm & Lọc Chi Tiết Bên Trái -->
            <form action="{{ route('welcome') }}" method="GET" id="sidebarFilterForm" class="p-3">
                <input type="hidden" name="sort" value="{{ request('sort', 'latest') }}" id="hiddenSortInput">
                
                <!-- 1. Ô Tìm Kiếm Từ Khóa -->
                <div class="mb-3">
                    <label class="filter-section-title">
                        <i class="fa-solid fa-magnifying-glass text-primary"></i> Từ khóa tìm kiếm
                    </label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="form-control" placeholder="Tên gạch, mã SKU, vân đá..."
                               style="border-radius: 8px 0 0 8px;">
                        <button type="submit" class="btn btn-primary" style="border-radius: 0 8px 8px 0;">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </div>

                <!-- 2. Danh Mục Sản Phẩm -->
                <div class="mb-3 pb-3 border-bottom" style="border-color: #ebdcd0 !important;">
                    <label class="filter-section-title">
                        <i class="fa-solid fa-layer-group text-primary"></i> Danh mục gạch
                    </label>
                    <div class="d-flex flex-column gap-1">
                        <a href="{{ route('welcome', request()->except('category_id', 'page')) }}" 
                           class="cat-item-link {{ !request('category_id') ? 'active' : '' }}">
                            <span>Tất cả danh mục</span>
                            <span class="badge-num">{{ $categories->sum('products_count') }}</span>
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('welcome', array_merge(request()->except('page'), ['category_id' => $cat->id])) }}" 
                               class="cat-item-link {{ request('category_id') == $cat->id ? 'active' : '' }}">
                                <span class="text-truncate" style="max-width: 170px;">{{ $cat->name }}</span>
                                <span class="badge-num">{{ $cat->products_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- 3. Lọc Kích Thước -->
                <div class="mb-3">
                    <label class="filter-section-title">
                        <i class="fa-solid fa-ruler-combined text-primary"></i> Kích thước gạch
                    </label>
                    <select name="size" class="form-select form-select-sm" onchange="document.getElementById('sidebarFilterForm').submit();">
                        <option value="">-- Tất cả kích cỡ --</option>
                        @foreach($sizes as $sz)
                            <option value="{{ $sz }}" {{ request('size') == $sz ? 'selected' : '' }}>
                                {{ $sz }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 4. Lọc Bề Mặt Men -->
                <div class="mb-3">
                    <label class="filter-section-title">
                        <i class="fa-solid fa-gem text-primary"></i> Loại bề mặt
                    </label>
                    <select name="surface" class="form-select form-select-sm" onchange="document.getElementById('sidebarFilterForm').submit();">
                        <option value="">-- Tất cả bề mặt --</option>
                        @foreach($surfaces as $sf)
                            <option value="{{ $sf }}" {{ request('surface') == $sf ? 'selected' : '' }}>
                                {{ $sf }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 5. Lọc Thương Hiệu -->
                <div class="mb-3">
                    <label class="filter-section-title">
                        <i class="fa-solid fa-award text-primary"></i> Thương hiệu
                    </label>
                    <select name="brand" class="form-select form-select-sm" onchange="document.getElementById('sidebarFilterForm').submit();">
                        <option value="">-- Tất cả thương hiệu --</option>
                        @foreach($brands as $br)
                            <option value="{{ $br }}" {{ request('brand') == $br ? 'selected' : '' }}>
                                {{ $br }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 6. Lọc Khoảng Giá -->
                <div class="mb-3">
                    <label class="filter-section-title">
                        <i class="fa-solid fa-tag text-primary"></i> Khoảng giá (VNĐ)
                    </label>
                    <div class="d-flex align-items-center gap-1 mb-2">
                        <input type="number" name="min_price" id="inputMinPrice" value="{{ request('min_price') }}" 
                               class="form-control form-control-sm" placeholder="Từ..." min="0" step="10000">
                        <span class="text-muted">-</span>
                        <input type="number" name="max_price" id="inputMaxPrice" value="{{ request('max_price') }}" 
                               class="form-control form-control-sm" placeholder="Đến..." min="0" step="10000">
                    </div>
                    <!-- Preset Giá Nhanh -->
                    <div class="d-flex flex-wrap gap-1">
                        <button type="button" class="price-preset-btn" onclick="setPriceFilter(0, 300000)">&lt; 300k</button>
                        <button type="button" class="price-preset-btn" onclick="setPriceFilter(300000, 450000)">300k - 450k</button>
                        <button type="button" class="price-preset-btn" onclick="setPriceFilter(450000, '')">&gt; 450k</button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2 pt-2">
                    <button type="submit" class="btn btn-primary btn-sm fw-bold rounded-pill">
                        <i class="fa-solid fa-filter me-1"></i> Áp Dụng Lọc
                    </button>
                    @if($activeFiltersCount > 0)
                        <a href="{{ route('welcome') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                            <i class="fa-solid fa-rotate-left me-1"></i> Đặt lại tất cả
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- DANH SÁCH SẢN PHẨM BÊN PHẢI (Right Product Catalog Column) -->
    <div class="col-lg-8 col-xl-9" id="productCatalogCol">
        
        <!-- Top Toolbar Điều Khiển Bên Phải -->
        <div class="filter-sidebar-card p-3 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <!-- Nút Mở Lại Bộ Lọc khi đã thu gọn sang trái -->
                <button type="button" class="filter-btn-toggle" id="btnOpenSidebar" style="display: none;">
                    <i class="fa-solid fa-sliders text-warning"></i>
                    <span>Mở Bộ Lọc</span>
                    @if($activeFiltersCount > 0)
                        <span class="badge bg-warning text-dark rounded-pill px-2">{{ $activeFiltersCount }}</span>
                    @endif
                </button>

                <h2 class="h5 fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <span>Danh Sách Sản Phẩm</span>
                    <span class="badge bg-secondary-subtle text-secondary fw-normal rounded-pill" style="font-size: 0.8rem;">
                        {{ $products->total() }} kết quả
                    </span>
                </h2>
            </div>

            <!-- Sắp Xếp Nhanh -->
            <div class="d-flex align-items-center gap-2">
                <label class="small text-muted fw-semibold d-none d-sm-inline">Sắp xếp:</label>
                <select class="form-select form-select-sm" style="min-width: 175px; border-radius: 10px;" 
                        onchange="changeSorting(this.value);">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên: A - Z</option>
                    <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Còn nhiều hàng nhất</option>
                </select>
            </div>
        </div>

        <!-- Nhãn Các Bộ Lọc Đang Hoạt Động (Active Filter Tags) -->
        @if($activeFiltersCount > 0)
            <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                <span class="small text-muted fw-bold me-1"><i class="fa-solid fa-filter me-1 text-primary"></i>Đang lọc theo:</span>
                
                @if(request('search'))
                    <span class="active-filter-badge">
                        Từ khóa: "{{ request('search') }}"
                        <a href="{{ route('welcome', request()->except('search', 'page')) }}"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                @endif

                @if(request('category_id'))
                    @php $activeCat = $categories->firstWhere('id', request('category_id')); @endphp
                    @if($activeCat)
                        <span class="active-filter-badge">
                            Danh mục: {{ $activeCat->name }}
                            <a href="{{ route('welcome', request()->except('category_id', 'page')) }}"><i class="fa-solid fa-xmark"></i></a>
                        </span>
                    @endif
                @endif

                @if(request('size'))
                    <span class="active-filter-badge">
                        Kích thước: {{ request('size') }}
                        <a href="{{ route('welcome', request()->except('size', 'page')) }}"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                @endif

                @if(request('surface'))
                    <span class="active-filter-badge">
                        Bề mặt: {{ request('surface') }}
                        <a href="{{ route('welcome', request()->except('surface', 'page')) }}"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                @endif

                @if(request('brand'))
                    <span class="active-filter-badge">
                        Thương hiệu: {{ request('brand') }}
                        <a href="{{ route('welcome', request()->except('brand', 'page')) }}"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                @endif

                @if(request('min_price') || request('max_price'))
                    <span class="active-filter-badge">
                        Giá: {{ request('min_price') ? number_format(request('min_price'), 0, ',', '.') . 'đ' : '0đ' }} - {{ request('max_price') ? number_format(request('max_price'), 0, ',', '.') . 'đ' : '∞' }}
                        <a href="{{ route('welcome', request()->except('min_price', 'max_price', 'page')) }}"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                @endif

                <a href="{{ route('welcome') }}" class="small text-danger ms-2 fw-semibold text-decoration-none">
                    <i class="fa-solid fa-trash-can me-1"></i>Đặt lại tất cả
                </a>
            </div>
        @endif

        <!-- Grid Sản Phẩm -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4" id="productsGridRow">
            @forelse ($products as $index => $product)
                <div class="col" style="animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) backwards; animation-delay: {{ min($index * 0.05, 0.5) }}s;">
                    <div class="card h-100 product-card d-flex flex-column">
                        <!-- Thumbnail với Image Zoom & Badges -->
                        <div class="product-img-wrap">
                            @if ($product->thumbnail)
                                <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}" loading="lazy">
                            @else
                                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted">
                                    <i class="fa-regular fa-image fs-1 mb-2 opacity-50"></i>
                                    <span class="small">Chưa có ảnh</span>
                                </div>
                            @endif

                            <!-- Stock Status Badge -->
                            <div class="position-absolute top-0 end-0 p-2">
                                @if($product->stock > 0)
                                    <span class="badge bg-success shadow-sm rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                        Còn hàng ({{ $product->stock }})
                                    </span>
                                @else
                                    <span class="badge bg-danger shadow-sm rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                        Tạm hết hàng
                                    </span>
                                @endif
                            </div>

                            <!-- Category Badge -->
                            @if($product->category)
                                <div class="position-absolute bottom-0 start-0 p-2">
                                    <span class="badge shadow-sm rounded-pill px-2 py-1" 
                                          style="background: rgba(31, 20, 14, 0.88); color: #e0a96d; border: 1px solid rgba(224, 169, 109, 0.35); font-size: 0.72rem;">
                                        <i class="fa-solid fa-shapes me-1"></i>{{ $product->category->name }}
                                    </span>
                                </div>
                            @endif

                            <!-- Sale Discount Badge -->
                            @if($product->sale_price && $product->sale_price < $product->price)
                                @php $discount = round((($product->price - $product->sale_price) / $product->price) * 100); @endphp
                                <div class="position-absolute top-0 start-0 p-2">
                                    <span class="badge bg-danger text-white rounded-pill px-2 py-1 fw-bold shadow-sm" style="font-size: 0.72rem;">
                                        -{{ $discount }}%
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-3 d-flex flex-column">
                            <!-- Brand & SKU -->
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.72rem; color: #8b5e3c !important;">
                                    {{ $product->brand ?? 'Chính Hãng' }}
                                </span>
                                @if($product->sku)
                                    <span class="badge-sku" style="font-size: 0.7rem; padding: 2px 6px;">{{ $product->sku }}</span>
                                @endif
                            </div>

                            <h5 class="card-title fw-bold text-dark fs-6 mb-2 text-truncate-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 40px; line-height: 1.3;" title="{{ $product->name }}">
                                {{ $product->name }}
                            </h5>

                            <!-- Quick Specs Tags -->
                            <div class="d-flex flex-wrap gap-1 mb-2">
                                @if($product->size)
                                    <span class="spec-tag" title="Kích thước">
                                        <i class="fa-solid fa-ruler-combined text-primary" style="font-size: 0.65rem;"></i> {{ $product->size }}
                                    </span>
                                @endif
                                @if($product->surface)
                                    <span class="spec-tag" title="Bề mặt">
                                        <i class="fa-solid fa-gem text-primary" style="font-size: 0.65rem;"></i> {{ Str::limit($product->surface, 15) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Price -->
                            <div class="mt-auto mb-3">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <div class="d-flex align-items-baseline gap-2">
                                        <span class="fw-bold fs-5 text-danger">
                                            {{ number_format($product->sale_price, 0, ',', '.') }} đ
                                        </span>
                                        <span class="text-muted text-decoration-line-through small" style="font-size: 0.8rem;">
                                            {{ number_format($product->price, 0, ',', '.') }} đ
                                        </span>
                                    </div>
                                    <div class="text-muted small" style="font-size: 0.75rem;">Đơn vị: /{{ $product->unit ?? 'Thùng' }}</div>
                                @else
                                    <div class="fw-bold fs-5" style="color: #8b5e3c;">
                                        {{ number_format($product->price, 0, ',', '.') }} <small class="fs-6 text-muted">đ/{{ $product->unit ?? 'Thùng' }}</small>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="pt-2 border-top d-flex gap-2" style="border-color: var(--border-color) !important;">
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary btn-sm flex-grow-1 fw-bold rounded-pill py-2">
                                    <i class="fa-solid fa-eye me-1"></i> Xem chi tiết
                                </a>
                                @auth
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-warning btn-sm rounded-pill px-3" title="Chỉnh sửa sản phẩm trong Admin">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 w-100">
                    <div class="card text-center p-5 border-0 shadow-sm rounded-4 animate-fade-up bg-white">
                        <i class="fa-solid fa-box-open text-muted mb-3" style="font-size: 4rem; opacity: 0.4;"></i>
                        <h5 class="fw-bold text-dark">Không tìm thấy sản phẩm gạch phù hợp</h5>
                        <p class="text-muted small mb-3">Vui lòng thử điều chỉnh hoặc xóa bớt các tiêu chí lọc để xem thêm sản phẩm.</p>
                        <div>
                            <a href="{{ route('welcome') }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">
                                <i class="fa-solid fa-rotate-left me-1"></i> Xóa tất cả bộ lọc
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Phân trang Bootstrap 5 đồng bộ -->
        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function setPriceFilter(min, max) {
        document.getElementById('inputMinPrice').value = min;
        document.getElementById('inputMaxPrice').value = max;
        document.getElementById('sidebarFilterForm').submit();
    }

    function changeSorting(sortVal) {
        document.getElementById('hiddenSortInput').value = sortVal;
        document.getElementById('sidebarFilterForm').submit();
    }

    // Toggle Sidebar Đóng / Mở sang bên trái
    document.addEventListener('DOMContentLoaded', function () {
        const filterCol = document.getElementById('filterSidebarCol');
        const catalogCol = document.getElementById('productCatalogCol');
        const btnToggleSidebar = document.getElementById('btnToggleSidebar');
        const btnOpenSidebar = document.getElementById('btnOpenSidebar');
        const productsGridRow = document.getElementById('productsGridRow');

        function hideSidebar() {
            if (filterCol) filterCol.style.display = 'none';
            if (catalogCol) {
                catalogCol.className = 'col-12';
            }
            if (productsGridRow) {
                productsGridRow.className = 'row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4';
            }
            if (btnOpenSidebar) btnOpenSidebar.style.display = 'inline-flex';
            localStorage.setItem('tile_filter_sidebar_visible', '0');
        }

        function showSidebar() {
            if (filterCol) filterCol.style.display = 'block';
            if (catalogCol) {
                catalogCol.className = 'col-lg-8 col-xl-9';
            }
            if (productsGridRow) {
                productsGridRow.className = 'row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4';
            }
            if (btnOpenSidebar) btnOpenSidebar.style.display = 'none';
            localStorage.setItem('tile_filter_sidebar_visible', '1');
        }

        if (btnToggleSidebar) {
            btnToggleSidebar.addEventListener('click', hideSidebar);
        }

        if (btnOpenSidebar) {
            btnOpenSidebar.addEventListener('click', showSidebar);
        }

        // Nhớ trạng thái đóng mở từ lần trước
        const savedState = localStorage.getItem('tile_filter_sidebar_visible');
        if (savedState === '0' && window.innerWidth >= 992) {
            hideSidebar();
        }
    });
</script>
@endsection
