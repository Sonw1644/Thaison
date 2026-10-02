@extends('layouts.admin')

@section('title', 'Thêm Mới Sản Phẩm Gạch - Admin Portal')
@section('page_title', 'Thêm Mới Sản Phẩm Gạch')

@section('content')
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="admin-card">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
            <div>
                <h5 class="fw-bold mb-1 text-dark">Thêm Mới Sản Phẩm Gạch Ốp Lát</h5>
                <p class="text-muted small mb-0">Nhập đầy đủ thông tin cơ bản, thông số kỹ thuật và màu sắc</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>

        <div class="row g-4">
            <!-- Cột trái: Thông tin chính & Thông số -->
            <div class="col-lg-8">
                <!-- Thông tin cơ bản -->
                <div class="card p-3 mb-3 border bg-white shadow-none">
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="fa-solid fa-circle-info me-1"></i> Thông Tin Cơ Bản
                    </h6>
                    <div class="mb-3">
                        <label class="form-label">Tên sản phẩm gạch <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="VD: Gạch lát nền Marble Calacatta 80×80 cm" value="{{ old('name') }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Mã SKU <span class="text-danger">*</span></label>
                            <input type="text" name="sku" class="form-control" placeholder="VD: GVH-AS507Y" value="{{ old('sku') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Đơn vị tính <span class="text-danger">*</span></label>
                            <select name="unit" class="form-select">
                                <option value="Thùng (Hộp)" {{ old('unit') == 'Thùng (Hộp)' ? 'selected' : '' }}>Thùng (Hộp)</option>
                                <option value="m²" {{ old('unit') == 'm²' ? 'selected' : '' }}>m² (Mét vuông)</option>
                                <option value="Viên" {{ old('unit') == 'Viên' ? 'selected' : '' }}>Viên</option>
                                <option value="Bộ" {{ old('unit') == 'Bộ' ? 'selected' : '' }}>Bộ</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Giá niêm yết (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control" placeholder="VD: 450000" value="{{ old('price') }}" required min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Giá khuyến mãi (nếu có)</label>
                            <input type="number" name="sale_price" class="form-control" placeholder="VD: 400000" value="{{ old('sale_price') }}" min="0">
                        </div>
                    </div>
                </div>

                <!-- Quy cách đóng gói & Thông số -->
                <div class="card p-3 mb-3 border bg-white shadow-none">
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="fa-solid fa-ruler-combined me-1"></i> Quy Cách Đóng Gói & Kích Thước
                    </h6>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Kích thước</label>
                            <input type="text" name="size" class="form-control" placeholder="VD: 800×800 mm" value="{{ old('size', '800×800 mm') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Diện tích/thùng (m²)</label>
                            <input type="number" step="0.001" name="area_per_box" class="form-control" placeholder="1.92" value="{{ old('area_per_box', '1.920') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Số viên/thùng</label>
                            <input type="number" name="pieces_per_box" class="form-control" placeholder="3" value="{{ old('pieces_per_box', '3') }}">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Bề mặt</label>
                            <input type="text" name="surface" class="form-control" placeholder="VD: Men mờ (Matt)" value="{{ old('surface', 'Men mờ (Matt)') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Chất liệu</label>
                            <input type="text" name="material" class="form-control" placeholder="VD: Porcelain" value="{{ old('material', 'Porcelain') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Họa tiết hoa văn</label>
                            <input type="text" name="color_pattern" class="form-control" placeholder="VD: Vân đá, vân gỗ, đa sắc..." value="{{ old('color_pattern') }}">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Xuất xứ</label>
                            <input type="text" name="origin" class="form-control" placeholder="VD: Việt Nam" value="{{ old('origin', 'Việt Nam') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Độ chống trơn</label>
                            <input type="text" name="anti_slip" class="form-control" placeholder="VD: R9" value="{{ old('anti_slip', 'R9') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Trọng lượng (kg/thùng)</label>
                            <input type="number" step="0.1" name="weight_per_box" class="form-control" placeholder="45" value="{{ old('weight_per_box', '45') }}">
                        </div>
                    </div>
                </div>

                <!-- Biến thể màu sắc -->
                <div class="card p-3 mb-3 border bg-white shadow-none">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-primary mb-0">
                            <i class="fa-solid fa-palette me-1"></i> Biến Thể Màu Sắc & Vân Gạch
                        </h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddVariant">
                            <i class="fa-solid fa-plus me-1"></i> Thêm màu
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0" id="variantTable">
                            <thead class="table-light">
                                <tr style="font-size: 0.82rem;">
                                    <th style="width: 60px;" class="text-center">Màu</th>
                                    <th>Tên màu / Vân gạch</th>
                                    <th>Ảnh tương ứng</th>
                                    <th style="width: 120px;">Mã SKU</th>
                                    <th style="width: 90px;">Tồn kho</th>
                                    <th style="width: 40px;" class="text-center">Xóa</th>
                                </tr>
                            </thead>
                            <tbody id="variantContainer">
                                <tr class="variant-row">
                                    <td class="text-center p-1">
                                        <input type="color" name="variants[0][color_code]" class="form-control form-control-color w-100 p-0 border-0" value="#787878" style="height: 34px;">
                                    </td>
                                    <td>
                                        <input type="text" name="variants[0][color_name]" class="form-control form-control-sm" placeholder="VD: Xám Bê Tông" value="Xám Bê Tông" required>
                                    </td>
                                    <td>
                                        <input type="file" name="variants[0][image]" class="form-control form-control-sm" accept="image/*">
                                    </td>
                                    <td>
                                        <input type="text" name="variants[0][sku]" class="form-control form-control-sm" placeholder="SKU-GRY" value="SKU-GRY">
                                    </td>
                                    <td>
                                        <input type="number" name="variants[0][stock]" class="form-control form-control-sm" placeholder="100" value="100" min="0">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm text-danger p-0 btn-remove-variant">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr class="variant-row">
                                    <td class="text-center p-1">
                                        <input type="color" name="variants[1][color_code]" class="form-control form-control-color w-100 p-0 border-0" value="#ffffff" style="height: 34px;">
                                    </td>
                                    <td>
                                        <input type="text" name="variants[1][color_name]" class="form-control form-control-sm" placeholder="VD: Trắng Vân Mây" value="Trắng Vân Mây" required>
                                    </td>
                                    <td>
                                        <input type="file" name="variants[1][image]" class="form-control form-control-sm" accept="image/*">
                                    </td>
                                    <td>
                                        <input type="text" name="variants[1][sku]" class="form-control form-control-sm" placeholder="SKU-WHT" value="SKU-WHT">
                                    </td>
                                    <td>
                                        <input type="number" name="variants[1][stock]" class="form-control form-control-sm" placeholder="100" value="100" min="0">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm text-danger p-0 btn-remove-variant">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mô tả -->
                <div class="card p-3 mb-3 border bg-white shadow-none">
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="fa-solid fa-align-left me-1"></i> Mô Tả Sản Phẩm
                    </h6>
                    <div class="mb-3">
                        <label class="form-label">Mô tả ngắn</label>
                        <textarea name="short_description" class="form-control" rows="2" placeholder="Tóm tắt thông tin sản phẩm...">{{ old('short_description') }}</textarea>
                    </div>
                    <div>
                        <label class="form-label">Mô tả chi tiết</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Chi tiết về sản phẩm, hướng dẫn ốp lát...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Phân loại, Ảnh & Lưu -->
            <div class="col-lg-4">
                <!-- Ảnh đại diện chính -->
                <div class="card p-3 mb-3 border bg-white shadow-none">
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="fa-solid fa-image me-1"></i> Ảnh Đại Diện Sản Phẩm
                    </h6>
                    <input type="file" name="thumbnail" id="thumbnailInput" class="form-control" accept="image/*">
                    <div id="thumbnailPreviewContainer" class="d-none mt-2 text-center p-2 border rounded bg-light">
                        <img id="thumbnailPreview" src="#" alt="Preview" class="img-fluid rounded" style="max-height: 140px; object-fit: contain;">
                    </div>
                </div>

                <div class="card p-3 border bg-white shadow-none sticky-top" style="top: 20px;">
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="fa-solid fa-boxes-stacked me-1"></i> Phân Loại & Kho
                    </h6>

                    <div class="mb-3">
                        <label class="form-label">Hạng mục sản phẩm <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Chọn hạng mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Thương hiệu</label>
                        <input type="text" name="brand" class="form-control" placeholder="VD: Catalan, Taicera..." value="{{ old('brand') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tổng số lượng tồn kho <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control" placeholder="VD: 200" value="{{ old('stock', '200') }}" required min="0">
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="status">Kích hoạt hiển thị (Đang bán)</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Sản Phẩm
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Thumbnail preview
        const thumbInput = document.getElementById('thumbnailInput');
        const thumbPreview = document.getElementById('thumbnailPreview');
        const previewContainer = document.getElementById('thumbnailPreviewContainer');

        if (thumbInput) {
            thumbInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        thumbPreview.src = e.target.result;
                        previewContainer.classList.remove('d-none');
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        let variantIndex = 2;
        const btnAddVariant = document.getElementById('btnAddVariant');
        const variantContainer = document.getElementById('variantContainer');

        btnAddVariant.addEventListener('click', function () {
            const tr = document.createElement('tr');
            tr.className = 'variant-row';
            tr.innerHTML = `
                <td class="text-center p-1">
                    <input type="color" name="variants[${variantIndex}][color_code]" class="form-control form-control-color w-100 p-0 border-0" value="#6b7280" style="height: 34px;">
                </td>
                <td>
                    <input type="text" name="variants[${variantIndex}][color_name]" class="form-control form-control-sm" placeholder="Tên màu..." required>
                </td>
                <td>
                    <input type="file" name="variants[${variantIndex}][image]" class="form-control form-control-sm" accept="image/*">
                </td>
                <td>
                    <input type="text" name="variants[${variantIndex}][sku]" class="form-control form-control-sm" placeholder="SKU...">
                </td>
                <td>
                    <input type="number" name="variants[${variantIndex}][stock]" class="form-control form-control-sm" placeholder="100" value="100" min="0">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm text-danger p-0 btn-remove-variant">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
            `;
            variantContainer.appendChild(tr);
            variantIndex++;
        });

        variantContainer.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.btn-remove-variant');
            if (removeBtn) {
                const rows = variantContainer.querySelectorAll('.variant-row');
                if (rows.length > 1) {
                    removeBtn.closest('.variant-row').remove();
                } else {
                    Swal.fire('Thông báo', 'Cần giữ ít nhất 1 biến thể màu sắc!', 'info');
                }
            }
        });
    });
</script>
@endsection
