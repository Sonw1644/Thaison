@extends('layouts.admin')

@section('title', 'Quản Lý Sản Phẩm Gạch Ốp Lát - Admin Portal')
@section('page_title', 'Quản Lý Sản Phẩm Gạch Ốp Lát')

@section('content')
<div class="admin-card">
    <!-- Top Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-shapes text-primary fs-5"></i>
            <h2 class="fs-5 fw-bold mb-0 text-dark">Danh Sách Sản Phẩm ({{ $totalCount }})</h2>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Thêm sản phẩm mới</span>
        </a>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('admin.products.index') }}" method="GET" class="row g-2 align-items-center mb-4">
        <div class="col-md-4 col-sm-12">
            <div class="input-group">
                <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm tên gạch, mã SKU..." value="{{ request('keyword') }}">
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <select name="category_id" class="form-select">
                <option value="">Tất cả danh mục</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 col-sm-6">
            <select name="stock_status" class="form-select">
                <option value="">Tất cả tồn kho</option>
                <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>Còn hàng (> 0)</option>
                <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Sắp hết hàng (≤ 50)</option>
                <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng (0)</option>
            </select>
        </div>
        <div class="col-md-2 col-sm-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary-dark w-100 fw-semibold">
                Lọc
            </button>
            @if(request()->hasAny(['keyword', 'category_id', 'stock_status']))
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary" title="Bỏ lọc">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </div>
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead>
                <tr class="table-custom-head">
                    <th style="width: 50px;">STT</th>
                    <th style="min-width: 280px;">Sản phẩm</th>
                    <th style="min-width: 130px;">Mã SKU</th>
                    <th style="min-width: 140px;">Danh mục</th>
                    <th style="min-width: 120px;">Giá niêm yết</th>
                    <th style="min-width: 110px;">Giá KM</th>
                    <th style="min-width: 110px;">Tồn kho</th>
                    <th style="min-width: 190px;">Quy cách</th>
                    <th class="text-center" style="min-width: 100px;">Trạng thái</th>
                    <th class="text-end" style="min-width: 90px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $index => $product)
                    <tr>
                        <!-- STT -->
                        <td class="text-muted fw-semibold">{{ $products->firstItem() + $index }}</td>

                        <!-- Sản phẩm -->
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($product->thumbnail)
                                    <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}" class="rounded-2 shadow-sm object-fit-cover flex-shrink-0" style="width: 48px; height: 48px; border: 1px solid #e2e8f0;">
                                @else
                                    <div class="rounded-2 d-flex align-items-center justify-content-center bg-light text-primary flex-shrink-0" style="width: 48px; height: 48px; border: 1px dashed #cbd5e1;">
                                        <i class="fa-solid fa-shapes"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold text-dark mb-1" style="font-size: 0.92rem; line-height: 1.35;">
                                        {{ $product->name }}
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted" style="font-size: 0.78rem;">{{ $product->brand ?: 'Chưa đặt hãng' }}</span>
                                        @if($product->variants->count() > 0)
                                            <span class="badge-colors">
                                                <i class="fa-solid fa-palette" style="font-size: 0.68rem;"></i>
                                                {{ $product->variants->count() }} màu
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Mã SKU -->
                        <td>
                            <span class="badge-sku">{{ $product->sku }}</span>
                        </td>

                        <!-- Danh mục -->
                        <td>
                            <span class="text-secondary fw-semibold" style="font-size: 0.88rem;">
                                {{ $product->category ? $product->category->name : 'Chưa phân loại' }}
                            </span>
                        </td>

                        <!-- Giá niêm yết -->
                        <td>
                            <span class="fw-bold text-dark" style="font-size: 0.92rem;">
                                {{ number_format($product->price, 0, ',', '.') }} đ
                            </span>
                        </td>

                        <!-- Giá KM -->
                        <td>
                            @if($product->sale_price && $product->sale_price > 0)
                                <span class="fw-bold text-danger" style="font-size: 0.92rem;">
                                    {{ number_format($product->sale_price, 0, ',', '.') }} đ
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <!-- Tồn kho -->
                        <td>
                            @if($product->stock > 50)
                                <span class="badge-stock">
                                    {{ $product->stock }} {{ $product->unit == 'Thùng (Hộp)' ? 'thùng' : $product->unit }}
                                </span>
                            @elseif($product->stock > 0)
                                <span class="badge-stock warning">
                                    {{ $product->stock }} {{ $product->unit == 'Thùng (Hộp)' ? 'thùng' : $product->unit }}
                                </span>
                            @else
                                <span class="badge-stock danger">Hết hàng</span>
                            @endif
                        </td>

                        <!-- Quy cách -->
                        <td>
                            <div style="font-size: 0.84rem; color: #475569;">
                                @if($product->size)
                                    <span>{{ $product->size }}</span>
                                @endif
                                @if($product->area_per_box)
                                    <span class="text-muted"> • {{ number_format($product->area_per_box, 3, ',', '.') }} m²/thùng</span>
                                @endif
                            </div>
                        </td>

                        <!-- Trạng thái -->
                        <td class="text-center">
                            @if($product->status)
                                <span class="badge-status-active">Đang bán</span>
                            @else
                                <span class="badge-status-inactive">Tạm dừng</span>
                            @endif
                        </td>

                        <!-- Hành động -->
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('products.show', $product->id) }}" class="btn-action-edit text-info border-info" title="Xem chi tiết">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-action-edit" title="Chỉnh sửa">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline form-delete-confirm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-delete" title="Xóa">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-boxes-stacked fs-1 d-block mb-3 text-secondary"></i>
                            Không tìm thấy sản phẩm gạch nào phù hợp.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
