@extends('layouts.admin')

@section('title', 'Chỉnh sửa người dùng - Thái Sơn Admin')
@section('page_title', 'Chỉnh Sửa Người Dùng')

@section('content')
<div class="admin-card" style="max-width: 700px; margin: 0 auto;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Chỉnh Sửa Thông Tin Người Dùng</h4>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Địa chỉ Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mật khẩu mới (để trống nếu không muốn đổi)</label>
            <input type="password" name="password" class="form-control" placeholder="Để trống nếu giữ nguyên mật khẩu cũ">
        </div>

        <div class="mb-4">
            <label class="form-label">Vai trò <span class="text-danger">*</span></label>
            <select name="role" class="form-select" required>
                <option value="user" @selected(old('role', $user->role) === 'user')>Khách hàng (User)</option>
                <option value="admin" @selected(old('role', $user->role) === 'admin')>Quản trị viên (Admin)</option>
            </select>
        </div>

        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Hủy bỏ</a>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-check me-1"></i> Cập nhật
            </button>
        </div>
    </form>
</div>
@endsection
