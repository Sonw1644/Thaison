@extends('layouts.admin')

@section('title', 'Quản lý người dùng - Thái Sơn Admin')
@section('page_title', 'Quản Lý Người Dùng')

@section('content')
<div class="admin-card mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h4 class="fw-bold mb-1">Danh Sách Tài Khoản Người Dùng</h4>
            <p class="text-muted mb-0 small">Quản lý toàn bộ danh sách tài khoản khách hàng và quản trị viên trong hệ thống.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-user-plus me-1"></i> + Thêm người dùng
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-custom-head">
                <tr>
                    <th style="width: 60px;" class="text-center">STT</th>
                    <th>Tên người dùng</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Ngày tạo</th>
                    <th class="text-end" style="width: 160px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td class="text-center text-muted fw-bold">
                        {{ method_exists($users, 'firstItem') ? ($users->firstItem() + $index) : ($index + 1) }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white" 
                                 style="width: 34px; height: 34px; background-color: {{ $user->role === 'admin' ? '#8b5e3c' : '#0284c7' }}; font-size: 0.85rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                <div class="text-muted small" style="font-size: 0.75rem;">ID: #{{ $user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">
                                <i class="fa-solid fa-shield-halved me-1"></i> Quản trị viên
                            </span>
                        @else
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill">
                                <i class="fa-solid fa-user me-1"></i> Khách hàng
                            </span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn-action-edit" title="Chi tiết">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action-edit" title="Chỉnh sửa">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline form-delete-confirm" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-delete" title="Xóa">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Không có người dùng nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($users, 'hasPages') && $users->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
