@extends('layouts.admin')

@section('title', 'Quản Lý Hạng Mục Gạch - Admin Portal')
@section('page_title', 'Quản Lý Hạng Mục Gạch')

@section('content')
<div class="admin-card">
    <!-- Header of Card -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-layer-group text-primary fs-5"></i>
            <h2 class="fs-5 fw-bold mb-0 text-dark">Danh Sách Hạng Mục Gạch</h2>
        </div>
        <button type="button" class="btn btn-primary-dark d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="fa-solid fa-plus"></i>
            <span>Thêm hạng mục mới</span>
        </button>
    </div>

    <!-- Categories Table -->
    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead>
                <tr class="table-custom-head">
                    <th style="width: 60px;">STT</th>
                    <th style="min-width: 200px;">Tên hạng mục gạch</th>
                    <th>Mô tả chi tiết</th>
                    <th class="text-center" style="width: 140px;">Số sản phẩm</th>
                    <th class="text-center" style="width: 90px;">Thứ tự</th>
                    <th class="text-center" style="width: 120px;">Trạng thái</th>
                    <th class="text-end" style="width: 100px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $index => $cat)
                    <tr>
                        <td class="text-muted fw-semibold">{{ $categories->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $cat->name }}</div>
                            <div class="text-muted small" style="font-size: 0.78rem;">Mã: #{{ $cat->id }}</div>
                        </td>
                        <td class="text-secondary" style="font-size: 0.88rem; max-width: 350px;">
                            {{ $cat->description ?: 'Chưa có mô tả' }}
                        </td>
                        <td class="text-center">
                            <span class="badge-count-products">
                                {{ $cat->products_count }} sản phẩm
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border px-2 py-1">{{ $cat->sort_order }}</span>
                        </td>
                        <td class="text-center">
                            @if($cat->status)
                                <span class="badge-status-active">Hiển thị</span>
                            @else
                                <span class="badge-status-inactive">Ẩn</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <button type="button" 
                                        class="btn-action-edit btn-edit-category" 
                                        data-id="{{ $cat->id }}"
                                        data-name="{{ $cat->name }}"
                                        data-description="{{ $cat->description }}"
                                        data-sort_order="{{ $cat->sort_order }}"
                                        data-status="{{ $cat->status ? '1' : '0' }}"
                                        data-url="{{ route('admin.categories.update', $cat->id) }}"
                                        title="Chỉnh sửa">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="d-inline form-delete-confirm">
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
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fa-solid fa-box-open fs-2 d-block mb-2 text-secondary"></i>
                            Chưa có hạng mục gạch nào. Hãy nhấn <strong>Thêm hạng mục mới</strong>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-3">
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>
</div>

<!-- Modal Thêm Hạng Mục Mới -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 14px;">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="createCategoryModalLabel">
                        <i class="fa-solid fa-plus-circle text-primary me-2"></i>Thêm Mới Hạng Mục Gạch
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label class="form-label">Tên hạng mục gạch <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="VD: Gạch lát nền 80×80 cm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả chi tiết</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Mô tả thông tin dòng sản phẩm, vị trí sử dụng..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thứ tự hiển thị</label>
                            <input type="number" name="sort_order" class="form-control" value="1" min="1">
                        </div>
                        <div class="col-md-6 mb-3 d-flex flex-column justify-content-center">
                            <label class="form-label">Trạng thái</label>
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" name="status" id="statusCreate" value="1" checked>
                                <label class="form-check-label fw-semibold" for="statusCreate">Hiển thị danh mục</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-primary-dark px-4">Lưu hạng mục</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa Hạng Mục -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 14px;">
            <form id="formEditCategory" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="editCategoryModalLabel">
                        <i class="fa-solid fa-pen-to-square text-primary me-2"></i>Chỉnh Sửa Hạng Mục Gạch
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label class="form-label">Tên hạng mục gạch <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editCategoryName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả chi tiết</label>
                        <textarea name="description" id="editCategoryDescription" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thứ tự hiển thị</label>
                            <input type="number" name="sort_order" id="editCategorySortOrder" class="form-control" min="1">
                        </div>
                        <div class="col-md-6 mb-3 d-flex flex-column justify-content-center">
                            <label class="form-label">Trạng thái</label>
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" name="status" id="editCategoryStatus" value="1">
                                <label class="form-check-label fw-semibold" for="editCategoryStatus">Hiển thị danh mục</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-primary-dark px-4">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.btn-edit-category');
        const editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
        const formEdit = document.getElementById('formEditCategory');
        const editName = document.getElementById('editCategoryName');
        const editDesc = document.getElementById('editCategoryDescription');
        const editSort = document.getElementById('editCategorySortOrder');
        const editStatus = document.getElementById('editCategoryStatus');

        editButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const url = this.getAttribute('data-url');
                const name = this.getAttribute('data-name');
                const desc = this.getAttribute('data-description');
                const sort = this.getAttribute('data-sort_order');
                const status = this.getAttribute('data-status');

                formEdit.action = url;
                editName.value = name || '';
                editDesc.value = desc || '';
                editSort.value = sort || '1';
                editStatus.checked = (status === '1');

                editModal.show();
            });
        });
    });
</script>
@endsection