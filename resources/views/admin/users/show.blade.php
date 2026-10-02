@extends('layouts.admin')

@section('title', 'Chi tiết người dùng - Thái Sơn Admin')
@section('page_title', 'Chi Tiết Người Dùng')

@section('content')
<div class="admin-card" style="max-width: 700px; margin: 0 auto;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Thông Tin Người Dùng #{{ $user->id }}</h4>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="card border-0 shadow-sm p-4 mb-4" style="background-color: #fdfbf7; border-radius: 12px; border: 1px solid var(--border-color);">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white fs-4" 
                 style="width: 56px; height: 56px; background-color: {{ $user->role === 'admin' ? '#8b5e3c' : '#0284c7' }};">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                <span class="text-muted">{{ $user->email }}</span>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-sm-6">
                <span class="text-muted small">Mã ID:</span>
                <div class="fw-bold fs-6">#{{ $user->id }}</div>
            </div>
            <div class="col-sm-6">
                <span class="text-muted small">Vai trò:</span>
                <div>
                    @if($user->role === 'admin')
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">
                            <i class="fa-solid fa-shield-halved me-1"></i> Quản trị viên
                        </span>
                    @else
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill">
                            <i class="fa-solid fa-user me-1"></i> Khách hàng
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <span class="text-muted small">Ngày đăng ký tài khoản:</span>
                <div class="fw-semibold">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i:s') : 'N/A' }}</div>
            </div>
            <div class="col-sm-6">
                <span class="text-muted small">Cập nhật lần cuối:</span>
                <div class="fw-semibold">{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 justify-content-end">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
            <i class="fa-solid fa-pen-to-square me-1"></i> Chỉnh sửa
        </a>
    </div>
</div>
@endsection
