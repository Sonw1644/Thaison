<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'THÁI SƠN - Hệ Thống Quản Trị')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Google Fonts: Inter, Be Vietnam Pro & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #2a1c15;
            --sidebar-hover: rgba(229, 178, 121, 0.12);
            --sidebar-text: #ded2c9;
            --sidebar-text-active: #ffffff;
            --sidebar-header: #96867d;
            --brand-brown: #54392b;
            --brand-brown-hover: #6b4a38;
            --brand-beige: #e5b279;
            --brand-beige-subtle: rgba(229, 178, 121, 0.18);
            --body-bg: #fdfbf7;
            --card-bg: #ffffff;
            --text-dark: #2a1c15;
            --border-color: #e9e2d8;
            --brown-dark-card: #38251c;
            --brown-dark-border: #54392b;
        }

        * {
            box-sizing: border-box;
            font-family: 'Be Vietnam Pro', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        h1, h2, h3, h4, h5, h6, .brand-title, .page-title, .admin-card h5 {
            font-family: 'Outfit', 'Be Vietnam Pro', sans-serif;
            letter-spacing: -0.02em;
        }

        /* Ghi đè toàn bộ màu xanh Bootstrap sang Nâu Be trong Admin */
        a {
            color: var(--brand-brown);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        a:hover {
            color: var(--brand-beige);
        }

        .text-primary, a.text-primary, span.text-primary, i.text-primary, strong.text-primary, h6.text-primary {
            color: #8b5e3c !important;
        }
        .text-info, a.text-info, span.text-info, i.text-info {
            color: var(--brand-beige) !important;
        }
        .bg-primary {
            background: linear-gradient(135deg, #261810 0%, #3d281d 100%) !important;
            color: #ffffff !important;
        }
        .bg-primary-subtle {
            background-color: rgba(224, 169, 109, 0.18) !important;
            color: #8b5e3c !important;
        }
        .border-primary, .border-primary-subtle {
            border-color: rgba(196, 139, 82, 0.4) !important;
        }
        .form-check-input:checked {
            background-color: var(--brand-brown) !important;
            border-color: var(--brand-brown) !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--brand-beige) !important;
            box-shadow: 0 0 0 0.25rem rgba(196, 139, 82, 0.2) !important;
        }
        /* Pagination styling */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-bottom: 0;
            padding: 0;
            list-style: none;
        }
        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            border-radius: 8px;
        }
        .pagination .page-link {
            min-width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid #e2d9cf;
            background-color: #ffffff;
            color: #54392b !important;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0 12px;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .pagination .page-link svg {
            width: 16px;
            height: 16px;
        }
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #261810 0%, #3d281d 100%) !important;
            border-color: #3d281d !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(38, 24, 16, 0.25);
        }
        .pagination .page-item.disabled .page-link {
            color: #a89f91 !important;
            background-color: #f7f4ef !important;
            border-color: #e9e2d8 !important;
            opacity: 0.6;
            cursor: not-allowed;
        }
        .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
            background-color: #f5efe6 !important;
            border-color: #c48b52 !important;
            color: #261810 !important;
            transform: translateY(-1px);
        }

        body {
            background-color: var(--body-bg);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            margin: 0;
            overflow-x: hidden;
        }

        /* Sidebar Styling Nâu Be */
        .admin-sidebar {
            width: 270px;
            min-height: 100vh;
            background: linear-gradient(180deg, #180f0b 0%, #261810 50%, #352219 100%);
            border-right: 1px solid var(--brown-dark-border);
            color: var(--sidebar-text);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            transition: all 0.3s ease;
            scrollbar-width: thin;
            scrollbar-color: #4a3528 transparent;
        }

        .admin-sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: #4a3528;
            border-radius: 4px;
        }

        .sidebar-brand-wrapper {
            padding: 24px 20px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }

        .brand-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3d281d 0%, #6c4e31 100%);
            border: 1px solid rgba(224, 169, 109, 0.4);
            color: var(--brand-beige);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(24, 15, 11, 0.5);
        }

        .brand-text-box {
            display: flex;
            flex-direction: column;
        }

        .brand-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: 0.5px;
            color: #ffffff;
            line-height: 1.2;
        }

        .brand-title span {
            color: var(--brand-beige);
        }

        .brand-subtitle {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.8px;
            color: #8b7970;
            text-transform: uppercase;
        }

        .sidebar-menu-scroll {
            flex: 1;
            padding: 0 14px 14px;
        }

        .sidebar-group-title {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.9px;
            color: #8b7970;
            padding: 22px 10px 8px;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-nav-item a {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 10px 14px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .sidebar-nav-item a i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
            color: var(--sidebar-text);
            transition: all 0.25s ease;
        }

        .sidebar-nav-item a:hover {
            color: #ffffff;
            background-color: var(--sidebar-hover);
            transform: translateX(4px);
        }

        .sidebar-nav-item a:hover i {
            color: var(--brand-beige);
            transform: scale(1.1);
        }

        .sidebar-nav-item.active a {
            background: linear-gradient(135deg, #4a3528 0%, #6c4e31 100%);
            border: 1px solid rgba(224, 169, 109, 0.4);
            color: #ffffff;
            font-weight: 700;
            border-radius: 20px;
            box-shadow: 0 4px 14px rgba(24, 15, 11, 0.4);
        }

        .sidebar-nav-item.active a i {
            color: var(--brand-beige);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .admin-main > * {
            animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) backwards;
        }

        .btn {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .btn:active {
            transform: scale(0.97) !important;
        }

        /* Sidebar Footer User */
        .sidebar-footer {
            padding: 16px 14px;
            border-top: 1px solid var(--brown-dark-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: rgba(0, 0, 0, 0.25);
        }

        .sidebar-user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            overflow: hidden;
        }

        .sidebar-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #c48b52 0%, #8b5e3c 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(139, 94, 60, 0.4);
        }

        .sidebar-user-meta {
            overflow: hidden;
            line-height: 1.2;
        }

        .sidebar-user-name {
            color: #ffffff;
            font-weight: 700;
            font-size: 0.84rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 140px;
        }

        .sidebar-user-role {
            color: var(--brand-beige);
            font-size: 0.72rem;
            font-weight: 600;
        }

        .btn-sidebar-logout {
            background: transparent;
            border: none;
            color: #d6c7be;
            font-size: 1.1rem;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .btn-sidebar-logout:hover {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.12);
        }

        /* Main Content */
        .admin-main {
            margin-left: 270px;
            flex: 1;
            padding: 24px 30px;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        /* Top Header Card Nâu Be */
        .top-header-card {
            background: linear-gradient(135deg, #1f140e 0%, #2d1e18 100%);
            border: 1px solid var(--brown-dark-border);
            border-radius: 16px;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(24, 15, 11, 0.2);
        }

        .page-title {
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0;
            color: #ffffff;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .user-avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(224, 169, 109, 0.2);
            color: var(--brand-beige);
            border: 1px solid rgba(224, 169, 109, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
        }

        .user-info {
            line-height: 1.25;
        }

        .user-name {
            font-weight: 700;
            font-size: 0.88rem;
            color: #ffffff;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--brand-beige);
            font-weight: 600;
        }

        /* Content Card */
        .admin-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(38, 24, 16, 0.04);
            border: 1px solid var(--border-color);
        }

        /* Primary Button */
        .btn-primary, .btn-primary-dark {
            background: linear-gradient(135deg, #261810 0%, #3d281d 100%);
            border: 1px solid #6c4e31;
            color: #fff;
            font-weight: 600;
            border-radius: 10px;
            padding: 9px 20px;
            box-shadow: 0 4px 12px rgba(38, 24, 16, 0.25);
            transition: all 0.2s;
        }

        .btn-primary:hover, .btn-primary-dark:hover {
            background: linear-gradient(135deg, #3d281d 0%, #503527 100%);
            border-color: #d4a373;
            color: #fff;
            box-shadow: 0 6px 16px rgba(38, 24, 16, 0.35);
        }

        .btn-outline-primary {
            border-color: #6c4e31;
            color: #6c4e31;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #261810 0%, #3d281d 100%);
            border-color: #6c4e31;
            color: #fff;
        }

        /* Custom Badges */
        .badge-sku {
            background-color: #f7f4ef;
            color: #78716c;
            font-family: monospace;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 6px;
            border: 1px solid #e7e0d6;
        }

        .badge-colors {
            background-color: rgba(224, 169, 109, 0.15);
            color: #8b5e3c;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-stock {
            background-color: #3d281d;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .badge-stock.warning {
            background-color: #d97706;
        }

        .badge-stock.danger {
            background-color: #dc2626;
        }

        .badge-count-products {
            background-color: rgba(224, 169, 109, 0.15);
            color: #8b5e3c;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
            border: 1px solid rgba(224, 169, 109, 0.35);
        }

        .badge-status-active {
            background-color: #dcfce7;
            color: #15803d;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            border: 1px solid #bbf7d0;
        }

        .badge-status-inactive {
            background-color: #f7f4ef;
            color: #78716c;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            border: 1px solid #e7e0d6;
        }

        /* Action Buttons */
        .btn-action-edit {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: #fff;
            border: 1px solid #8b5e3c;
            color: #8b5e3c;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-action-edit:hover {
            background: #3d281d;
            border-color: #3d281d;
            color: #fff;
        }

        .btn-action-delete {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: #fff;
            border: 1px solid #ef4444;
            color: #ef4444;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-action-delete:hover {
            background: #ef4444;
            color: #fff;
        }

        .table > :not(caption) > * > * {
            padding: 14px 12px;
            vertical-align: middle;
        }

        .table-custom-head {
            font-size: 0.82rem;
            font-weight: 700;
            color: #78716c;
            background-color: #fbf9f6;
            border-bottom: 1px solid #e7e0d6;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            padding: 9px 14px;
            font-size: 0.9rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--brand-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        @media (max-width: 991px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
                padding: 16px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <!-- Logo & Header -->
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand-wrapper">
            <div class="brand-text-box">
                <div class="brand-title">Gạch SOL<span></span></div>
                <div class="brand-subtitle">HỆ THỐNG QUẢN TRỊ</div>
            </div>
        </a>

        <!-- Menu Navigation -->
        <div class="sidebar-menu-scroll">
            <ul class="sidebar-nav" style="margin-top: 6px;">
                <li class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fa-solid fa-chart-pie"></i>
                        <span>Bảng điều khiển</span>
                    </a>
                </li>
            </ul>

            <!-- Quản lý kho hàng & sản phẩm -->
            <div class="sidebar-group-title">QUẢN LÝ BÁN HÀNG & KHO</div>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.orders.index') }}">
                        <i class="fa-solid fa-receipt"></i>
                        <span>Quản lý Đơn hàng</span>
                    </a>
                </li>
                <li class="sidebar-nav-item {{ request()->routeIs('admin.finance.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.finance.index') }}">
                        <i class="fa-solid fa-chart-column"></i>
                        <span>Thống kê tài chính</span>
                    </a>
                </li>
                <li class="sidebar-nav-item {{ request()->routeIs('admin.finance.transactions') ? 'active' : '' }}">
                    <a href="{{ route('admin.finance.transactions') }}">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                        <span>Giao dịch thanh toán</span>
                    </a>
                </li>
                <li class="sidebar-nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.products.index') }}">
                        <i class="fa-solid fa-couch"></i>
                        <span>Sản phẩm</span>
                    </a>
                </li>
                <li class="sidebar-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}">
                        <i class="fa-solid fa-list-ul"></i>
                        <span>Danh mục sản phẩm</span>
                    </a>
                </li>
                <li class="sidebar-nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.index') }}">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>Báo cáo doanh thu</span>
                    </a>
                </li>
            </ul>

            <!-- Hệ thống & Người dùng -->
            <div class="sidebar-group-title">HỆ THỐNG</div>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}">
                        <i class="fa-solid fa-users"></i>
                        <span>Quản lý Người dùng</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('welcome') }}" target="_blank">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        <span>Xem Website Khách</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Sidebar Footer User Profile -->
        <div class="sidebar-footer">
            <div class="sidebar-user-info">
                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'T', 0, 1)) }}
                </div>
                <div class="sidebar-user-meta">
                    <div class="sidebar-user-name" title="{{ Auth::user()->name ?? 'Thái Sơn' }}">
                        {{ Auth::user()->name ?? 'Thái Sơn' }}
                    </div>
                    <div class="sidebar-user-role">Super Admin</div>
                </div>
            </div>
            <button type="button" class="btn-sidebar-logout" title="Đăng xuất" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </button>
            <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </aside>

    <!-- Main Content Container -->
    <main class="admin-main">
        <!-- Top Header Card -->
        <header class="top-header-card">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary d-lg-none" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="page-title">@yield('page_title', 'Bảng Điều Khiển Quản Trị')</h1>
            </div>

            <div class="user-profile">
                <div class="user-avatar-circle">
                    {{ strtoupper(substr(Auth::user()->name ?? 'T', 0, 1)) }}
                </div>
                <div class="user-info d-none d-sm-block">
                    <div class="user-name">{{ Auth::user()->name ?? 'Thái Sơn' }}</div>
                    <div class="user-role">Super Admin</div>
                </div>
                <a href="{{ route('welcome') }}" class="btn btn-sm btn-outline-primary ms-2" target="_blank">
                    <i class="fa-solid fa-store me-1"></i> <span class="d-none d-md-inline">Xem Cửa Hàng</span>
                </a>
            </div>
        </header>

        <!-- Flash Message Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                <div class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-2"></i>Có lỗi xảy ra, vui lòng kiểm tra lại:</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main Yield View -->
        @yield('content')
    </main>

    <!-- Admin Livechat Popup -->
    <style>
        #admin-chat-box {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
        }
        #admin-chat-box #chat-toggle {
            font-weight: 600;
            padding: 10px 18px;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            background: linear-gradient(135deg, #261810 0%, #3d281d 100%);
            border: 1px solid rgba(224, 169, 109, 0.4);
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .chat-badge-dot {
            position: absolute;
            top: -6px;
            right: -4px;
            background-color: #ef4444;
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 800;
            min-width: 20px;
            height: 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.8);
            border: 2px solid #ffffff;
            z-index: 10;
        }
        .badge-ping {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: #ef4444;
            opacity: 0.75;
            animation: pingBadge 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
            pointer-events: none;
        }
        @keyframes pingBadge {
            75%, 100% {
                transform: scale(2.2);
                opacity: 0;
            }
        }
        #admin-chat-box #chat-popup {
            width: 620px;
            max-width: calc(100vw - 32px);
            height: 480px;
            border-radius: 16px;
            border: 1px solid #e9e2d8;
            display: none;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 10px 35px rgba(0,0,0,0.35);
            background: #ffffff;
        }
        .admin-chat-body {
            display: flex;
            flex: 1;
            overflow: hidden;
            background-color: #fff;
        }
        #user-list {
            width: 200px;
            border-right: 1px solid #e9e2d8;
            background-color: #fbf9f6;
            overflow-y: auto;
        }
        .user-item {
            padding: 10px 12px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            border-bottom: 1px solid #f0e8dc;
            transition: all 0.2s;
            color: #334155;
        }
        .user-item:hover {
            background-color: rgba(224, 169, 109, 0.15);
        }
        .user-item.active {
            background: linear-gradient(135deg, #3d281d 0%, #54392b 100%);
            color: #ffffff;
        }
        .user-item.active .badge-unread-user {
            background-color: #ef4444 !important;
            color: #ffffff !important;
        }
        .admin-chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #fdfbf7;
        }
        #admin-chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .admin-msg-row {
            padding: 7px 12px;
            border-radius: 10px;
            max-width: 85%;
            font-size: 0.85rem;
            line-height: 1.35;
            word-break: break-word;
        }
        .msg-from-admin {
            align-self: flex-end;
            background-color: #3d281d;
            color: #ffffff !important;
            border-bottom-right-radius: 2px;
        }
        .msg-from-user {
            align-self: flex-start;
            background-color: #e2e8f0;
            color: #1e293b !important;
            border-bottom-left-radius: 2px;
        }
    </style>

    <div id="admin-chat-box">
        <button id="chat-toggle" class="btn shadow position-relative">
            <span>💬 Chat Khách hàng</span>
            <span id="admin-chat-badge" class="chat-badge-dot d-none">
                <span class="badge-ping"></span>
                <span class="badge-num" id="admin-chat-badge-count">1</span>
            </span>
        </button>
        <div id="chat-popup" class="card shadow-lg">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-2 px-3">
                <strong class="d-flex align-items-center gap-2"><i class="fa-solid fa-headset text-warning"></i> Hỗ trợ trực tuyến</strong>
                <button id="chat-close" class="btn btn-sm btn-light py-0 px-2 fw-bold">✕</button>
            </div>
            <div class="admin-chat-body">
                <div id="user-list">
                    <div class="p-3 text-center text-muted small"><i class="fa-solid fa-spinner fa-spin me-1"></i> Đang tải danh sách...</div>
                </div>
                <div class="admin-chat-main">
                    <div id="admin-chat-messages">
                        <div class="text-center my-auto text-muted small"><i class="fa-solid fa-comment-dots fs-3 d-block mb-2"></i>Chọn một khách hàng để xem tin nhắn</div>
                    </div>
                    <div class="card-footer bg-white border-top p-2">
                        <div class="input-group">
                            <input type="text" id="admin-chat-input" class="form-control form-control-sm" placeholder="Nhập câu trả lời..." autocomplete="off">
                            <button id="admin-send-btn" class="btn btn-success btn-sm"><i class="fa-solid fa-paper-plane me-1"></i> Gửi</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    
    <script>
        // Global Delete confirmation with SweetAlert2
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.form-delete-confirm').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Xác nhận xóa?',
                        text: "Hành động này sẽ không thể khôi phục lại!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Đồng ý xóa',
                        cancelButtonText: 'Hủy bỏ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });

        // Admin Livechat Script
        let currentChatUserId = null;
        const adminChatPopup = document.getElementById("chat-popup");
        const adminChatToggle = document.getElementById("chat-toggle");
        const adminChatClose = document.getElementById("chat-close");
        const adminChatMessages = document.getElementById("admin-chat-messages");
        const adminChatInput = document.getElementById("admin-chat-input");
        const adminSendBtn = document.getElementById("admin-send-btn");
        const adminChatBadge = document.getElementById("admin-chat-badge");
        const adminChatBadgeCount = document.getElementById("admin-chat-badge-count");

        if (adminChatToggle) {
            adminChatToggle.onclick = () => {
                adminChatPopup.style.display = "flex";
                adminChatToggle.style.display = "none";
                if (adminChatBadge) adminChatBadge.classList.add("d-none");
                loadAdminChatUsers();
            };
        }

        if (adminChatClose) {
            adminChatClose.onclick = () => {
                adminChatPopup.style.display = "none";
                adminChatToggle.style.display = "flex";
                checkAdminUnreadCount();
            };
        }

        function checkAdminUnreadCount() {
            if (adminChatPopup && (adminChatPopup.style.display === "flex" || adminChatPopup.style.display === "block")) {
                return;
            }
            fetch("{{ route('admin.chat.unreadCount') }}")
                .then(res => res.json())
                .then(data => {
                    if (data.unread_count > 0) {
                        adminChatBadge.classList.remove("d-none");
                        adminChatBadgeCount.textContent = data.unread_count;
                    } else {
                        adminChatBadge.classList.add("d-none");
                    }
                })
                .catch(() => {});
        }

        function loadAdminChatUsers() {
            fetch("{{ route('admin.chat.users') }}")
                .then(res => res.json())
                .then(users => {
                    let html = "";
                    if (!users || users.length === 0) {
                        document.getElementById("user-list").innerHTML = '<div class="p-3 text-center text-muted small">Chưa có hội thoại</div>';
                        return;
                    }

                    users.forEach((user) => {
                        let activeClass = (currentChatUserId == user.id) ? 'active' : '';
                        let unreadBadge = (user.unread_count > 0) 
                            ? `<span class="badge bg-danger rounded-pill badge-unread-user px-2 py-1 shadow-sm">${user.unread_count}</span>` 
                            : '';
                        html += `<div class="user-item ${activeClass} d-flex align-items-center justify-content-between" onclick="selectChatUser(${user.id}, this)">
                            <div class="text-truncate me-2"><i class="fa-solid fa-user me-1 small"></i> ${user.name}</div>
                            ${unreadBadge}
                        </div>`;
                    });
                    document.getElementById("user-list").innerHTML = html;

                    // Tự động chọn user đầu tiên nếu chưa chọn ai
                    if (!currentChatUserId && users.length > 0) {
                        selectChatUser(users[0].id, document.querySelector('.user-item'));
                    }
                })
                .catch(err => console.error("Lỗi tải users:", err));
        }

        function selectChatUser(userId, element) {
            currentChatUserId = userId;
            document.querySelectorAll('.user-item').forEach(el => el.classList.remove('active'));
            if (element) {
                element.classList.add('active');
                // Xóa badge của user vừa chọn vì tin nhắn đã được load và đánh dấu đã đọc
                const userBadge = element.querySelector('.badge-unread-user');
                if (userBadge) userBadge.remove();
            }
            loadAdminChatMessages();
        }

        function loadAdminChatMessages() {
            if (!currentChatUserId) return;
            fetch(`{{ url('/admin/chat/messages') }}/${currentChatUserId}`)
                .then(res => res.json())
                .then(messages => {
                    let html = "";
                    if (messages.length === 0) {
                        html = "<div class='text-center my-auto text-muted small'>Chưa có tin nhắn trong cuộc trò chuyện này.</div>";
                    } else {
                        messages.forEach(msg => {
                            let isMe = msg.sender_id == "{{ Auth::id() }}";
                            let senderName = isMe ? "Bạn (Admin)" : (msg.sender ? msg.sender.name : "Khách hàng");
                            html += `
                                <div class="admin-msg-row ${isMe ? 'msg-from-admin' : 'msg-from-user'}">
                                    <small class="fw-bold d-block" style="font-size: 0.72rem; opacity: 0.85;">${senderName}</small>
                                    <span>${msg.content}</span>
                                </div>
                            `;
                        });
                    }
                    adminChatMessages.innerHTML = html;
                    adminChatMessages.scrollTop = adminChatMessages.scrollHeight;
                })
                .catch(err => console.error("Lỗi tải tin nhắn:", err));
        }

        function sendAdminMessage() {
            let message = adminChatInput.value.trim();
            if (!message || !currentChatUserId) return;

            adminChatInput.disabled = true;
            if (adminSendBtn) adminSendBtn.disabled = true;

            fetch("{{ route('admin.chat.send') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message: message,
                    user_id: currentChatUserId
                })
            })
            .then(res => res.json())
            .then(data => {
                adminChatInput.value = "";
                adminChatInput.disabled = false;
                if (adminSendBtn) adminSendBtn.disabled = false;
                adminChatInput.focus();
                loadAdminChatMessages();
            })
            .catch(err => {
                console.error("Lỗi gửi tin:", err);
                adminChatInput.disabled = false;
                if (adminSendBtn) adminSendBtn.disabled = false;
            });
        }

        if (adminSendBtn) adminSendBtn.onclick = sendAdminMessage;
        if (adminChatInput) {
            adminChatInput.onkeypress = (e) => {
                if (e.key === 'Enter') sendAdminMessage();
            };
        }

        // Khởi tạo kiểm tra tin nhắn chưa đọc
        checkAdminUnreadCount();

        // Polling mỗi 3 giây
        setInterval(() => {
            if (adminChatPopup && (adminChatPopup.style.display === "flex" || adminChatPopup.style.display === "block")) {
                loadAdminChatMessages();
                loadAdminChatUsers();
            } else {
                checkAdminUnreadCount();
            }
        }, 3000);
    </script>
    @yield('scripts')
</body>
</html>
