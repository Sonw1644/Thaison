<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SOL - Cửa Hàng Gạch Ốp Lát & Thiết Bị')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Be Vietnam Pro (Chữ hiển thị tiếng Việt mềm mại) & Outfit (Tiêu đề sang trọng) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --brown-950: #2a1c15;
            --brown-900: #38251c;
            --brown-850: #452e23;
            --brown-800: #54392b;
            --brown-700: #664736;
            --brown-600: #7d5843;
            --brown-500: #996d53;
            --beige-accent: #c8925b;
            --beige-glow: #e5b279;
            --beige-light: #f9f5ee;
            --beige-sand: #f1ebe1;
            --primary-color: #54392b;
            --primary-hover: #6b4a38;
            --primary-light: rgba(229, 178, 121, 0.18);
            --dark-color: #2a1c15;
            --text-muted: #7d756e;
            --bg-body: #fdfbf7;
            --card-bg: #ffffff;
            --border-color: #e9e2d8;
        }

        body {
            font-family: 'Be Vietnam Pro', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-body);
            color: var(--dark-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            letter-spacing: -0.01em;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand, .brand-title, .page-title, .card-title, .lead {
            font-family: 'Outfit', 'Be Vietnam Pro', sans-serif;
            letter-spacing: -0.02em;
        }

        /* Ghi đè toàn bộ màu xanh Bootstrap sang Nâu Be */
        a {
            color: var(--brown-600);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        a:hover {
            color: var(--beige-accent);
        }

        .text-primary, a.text-primary, span.text-primary, i.text-primary, strong.text-primary {
            color: var(--brown-600) !important;
        }
        .text-info, a.text-info, span.text-info, i.text-info {
            color: var(--beige-accent) !important;
        }
        .bg-primary {
            background: linear-gradient(135deg, var(--brown-900) 0%, var(--brown-800) 100%) !important;
            color: #ffffff !important;
        }
        .bg-primary-subtle {
            background-color: rgba(224, 169, 109, 0.18) !important;
            color: var(--brown-700) !important;
        }
        .border-primary, .border-primary-subtle {
            border-color: rgba(196, 139, 82, 0.4) !important;
        }

        /* Breadcrumb Nâu Be */
        .breadcrumb-item a {
            color: var(--brown-600) !important;
            font-weight: 600;
        }
        .breadcrumb-item a:hover {
            color: var(--beige-accent) !important;
        }
        .breadcrumb-item.active {
            color: var(--text-muted) !important;
            font-weight: 600;
        }

        /* Checkbox & Form Controls */
        .form-check-input:checked {
            background-color: var(--brown-700) !important;
            border-color: var(--brown-700) !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--beige-accent) !important;
            box-shadow: 0 0 0 0.25rem rgba(196, 139, 82, 0.2) !important;
        }

        /* Pagination Nâu Be */
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
            border: 1px solid var(--border-color);
            background-color: #ffffff;
            color: var(--brown-700) !important;
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
            background: linear-gradient(135deg, var(--brown-900) 0%, var(--brown-800) 100%) !important;
            border-color: var(--brown-800) !important;
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
            background-color: var(--beige-light) !important;
            border-color: var(--beige-accent) !important;
            color: var(--brown-900) !important;
            transform: translateY(-1px);
        }

        /* Navbar Màu Nâu Be Sang Trọng */
        .main-navbar {
            background: linear-gradient(135deg, var(--brown-950) 0%, var(--brown-850) 50%, var(--brown-800) 100%);
            border-bottom: 1px solid var(--brown-700);
            padding: 16px 0;
            position: sticky;
            top: 0;
            z-index: 1050;
            box-shadow: 0 4px 24px rgba(24, 15, 11, 0.4);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.32rem;
            color: #ffffff !important;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            letter-spacing: -0.3px;
            padding-right: 15px;
        }

        .navbar-brand i {
            color: var(--beige-glow);
            font-size: 1.55rem;
            filter: drop-shadow(0 0 8px rgba(224, 169, 109, 0.5));
        }

        .nav-link {
            font-weight: 600;
            font-size: 0.94rem;
            color: #ebdcd0 !important;
            padding: 9px 18px !important;
            border-radius: 50px;
            transition: all 0.25s ease;
        }

        .nav-link:hover {
            color: var(--beige-glow) !important;
            background-color: rgba(224, 169, 109, 0.14);
        }

        .nav-link.active {
            color: #ffffff !important;
            background: rgba(224, 169, 109, 0.22);
            border: 1px solid rgba(224, 169, 109, 0.35);
        }

        /* Cart Nav Button */
        .nav-cart-btn {
            background-color: rgba(224, 169, 109, 0.12);
            border: 1px solid rgba(224, 169, 109, 0.28);
            color: #ffffff !important;
            padding: 8px 18px !important;
        }

        .nav-cart-btn:hover {
            background-color: rgba(224, 169, 109, 0.24);
            border-color: var(--beige-glow);
            color: var(--beige-glow) !important;
        }

        .nav-btn-primary {
            background: linear-gradient(135deg, #c48b52 0%, #8b5e3c 100%);
            border: 1px solid rgba(224, 169, 109, 0.4);
            color: #ffffff !important;
            padding: 8px 20px !important;
            border-radius: 50px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        .nav-btn-primary:hover {
            background: linear-gradient(135deg, #d4a373 0%, #a0522d 100%);
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        .nav-btn-outline {
            border: 1px solid rgba(224, 169, 109, 0.35) !important;
            color: #ebdcd0 !important;
            padding: 8px 18px !important;
            border-radius: 50px;
            transition: all 0.25s ease;
        }

        .nav-btn-outline:hover {
            border-color: var(--beige-glow) !important;
            color: var(--beige-glow) !important;
            background-color: rgba(224, 169, 109, 0.15) !important;
        }

        /* User Dropdown */
        .user-dropdown-btn {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid var(--brown-700) !important;
            color: #ffffff !important;
            transition: all 0.25s ease;
        }

        .user-dropdown-btn:hover {
            border-color: var(--beige-glow) !important;
            background: rgba(224, 169, 109, 0.15) !important;
        }

        .dropdown-menu-dark-custom {
            background: var(--brown-900) !important;
            border: 1px solid var(--brown-700) !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.45) !important;
            border-radius: 12px;
            padding: 8px;
        }

        .dropdown-menu-dark-custom .dropdown-item {
            color: #ebdcd0 !important;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .main-content {
            flex: 1;
            padding: 42px 0 65px;
        }

        /* Animations & Keyframes */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes floatGently {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-6px);
            }
        }

        @keyframes pulseGlow {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(224, 169, 109, 0.4);
            }
            50% {
                box-shadow: 0 0 0 8px rgba(224, 169, 109, 0);
            }
        }

        @keyframes dropdownSlideIn {
            from {
                opacity: 0;
                transform: translateY(10px) scale(0.97);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes shimmerSweep {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        .animate-fade-up {
            animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-float {
            animation: floatGently 4s ease-in-out infinite;
        }

        /* Navbar & Dropdown Animation */
        .dropdown-menu {
            display: none;
        }
        .dropdown-menu.show {
            display: block;
            animation: dropdownSlideIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .navbar-brand i {
            animation: floatGently 3.5s ease-in-out infinite;
        }

        /* Nav link hover underline animation */
        .nav-link {
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: var(--beige-glow);
            transition: all 0.25s ease;
            transform: translateX(-50%);
            border-radius: 2px;
        }
        .nav-link:hover::after, .nav-link.active::after {
            width: 60%;
        }

        /* Buttons Micro-interactions */
        .btn {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .btn:active {
            transform: scale(0.97) !important;
        }

        /* Nút màu Nâu Be chính thức */
        .btn-primary {
            background: linear-gradient(135deg, var(--brown-900) 0%, var(--brown-800) 50%, var(--brown-700) 100%) !important;
            border: 1px solid var(--brown-600) !important;
            color: #ffffff !important;
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(38, 24, 16, 0.25) !important;
        }

        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(135deg, var(--brown-800) 0%, var(--brown-600) 100%) !important;
            border-color: var(--beige-glow) !important;
            color: #ffffff !important;
            box-shadow: 0 8px 22px rgba(61, 40, 29, 0.38) !important;
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            border: 1.5px solid var(--brown-700) !important;
            color: var(--brown-700) !important;
            font-weight: 600;
            background: transparent !important;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, var(--brown-900) 0%, var(--brown-800) 100%) !important;
            border-color: var(--brown-700) !important;
            color: #ffffff !important;
            transform: translateY(-2px);
        }

        /* Product Cards Rich Effects */
        .card-custom {
            background-color: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 18px;
            box-shadow: 0 4px 16px rgba(38, 24, 16, 0.04);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        .card-custom .card-img-container {
            overflow: hidden;
            position: relative;
        }

        .card-custom img {
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), filter 0.3s ease;
        }

        .card-custom:hover {
            box-shadow: 0 18px 36px rgba(38, 24, 16, 0.12);
            border-color: var(--beige-accent);
            transform: translateY(-6px);
        }

        .card-custom:hover img {
            transform: scale(1.07);
        }

        /* Table Row Interactive Hover */
        .table-hover tbody tr {
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .table-hover tbody tr:hover {
            background-color: #fbf8f4 !important;
        }

        /* Footer Nâu Be */
        .footer {
            background: linear-gradient(135deg, var(--brown-950) 0%, var(--brown-900) 100%);
            border-top: 1px solid var(--brown-700);
            padding: 28px 0;
            font-size: 0.92rem;
            color: #d6c7be;
            margin-top: auto;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Unified Navbar Nâu Be -->
    <nav class="navbar navbar-expand-lg main-navbar shadow-lg">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand" href="{{ route('welcome') }}">
                <i class="fa-solid fa-shapes"></i>
                <span>THÁI SƠN <small style="color: var(--beige-glow);" class="fs-6 fw-bold">Gạch</small></span>
            </a>

            <button class="navbar-toggler border-0 shadow-none text-light" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbarContent">
                <i class="fa-solid fa-bars fs-4 text-light"></i>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbarContent">
                <!-- Main Nav Links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}" href="{{ route('welcome') }}">
                            <i class="fa-solid fa-house me-1"></i> Trang chủ
                        </a>
                    </li>
                    
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <!-- Admin Dropdown Menu -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle fw-bold {{ request()->is('admin*') ? 'active' : '' }}" style="color: var(--navy-cyan) !important;" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-shield-halved me-1"></i> Quản Trị Hệ Thống
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark-custom shadow-lg border-0 mt-2">
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                            <i class="fa-solid fa-gauge-high text-info me-2"></i> Bảng điều khiển (Dashboard)
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.products.index') }}">
                                            <i class="fa-solid fa-boxes-stacked text-success me-2"></i> Quản lý Sản phẩm (Thêm/Sửa/Xóa)
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.categories.index') }}">
                                            <i class="fa-solid fa-layer-group text-warning me-2"></i> Quản lý Danh mục
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.orders.index') }}">
                                            <i class="fa-solid fa-receipt text-primary me-2"></i> Quản lý Đơn hàng
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endauth
                </ul>

                <!-- Auth Buttons & Profile -->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-2">
                    <!-- Giỏ hàng (Cart) -->
                    <li class="nav-item">
                        <a class="nav-link nav-cart-btn position-relative px-3 py-2 rounded-pill fw-semibold d-flex align-items-center gap-2 {{ request()->routeIs('cart.*') ? 'border-info' : '' }}" href="{{ route('cart.index') }}">
                            <i class="fa-solid fa-cart-shopping" style="color: var(--navy-cyan);"></i>
                            <span>Giỏ hàng</span>
                            @php $cartCount = count(session('cart', [])); @endphp
                            @if($cartCount > 0)
                                <span class="badge rounded-pill bg-danger" style="font-size: 0.75rem;">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    @guest
                        <li class="nav-item">
                            <a class="nav-link nav-btn-outline" href="{{ route('login') }}">
                                <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-btn-primary" href="{{ route('register') }}">
                                <i class="fa-solid fa-user-plus me-1"></i> Đăng ký
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle user-dropdown-btn d-flex align-items-center gap-2 px-3 py-2 rounded-pill" href="#" role="button" data-bs-toggle="dropdown">
                                <span class="rounded-circle text-white d-inline-flex justify-content-center align-items-center shadow-sm" style="background: linear-gradient(135deg, #c48b52 0%, #8b5e3c 100%); width: 28px; height: 28px; font-size: 0.8rem; font-weight: 700;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                <span>{{ Auth::user()->name }}</span>
                                <span class="badge {{ Auth::user()->role === 'admin' ? 'bg-danger' : 'bg-secondary' }} rounded-pill" style="font-size: 0.7rem;">
                                    {{ ucfirst(Auth::user()->role) }}
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark-custom dropdown-menu-end shadow-lg border-0 mt-2">
                                <li class="px-3 py-2 text-muted small border-bottom border-secondary">
                                    Đăng nhập với: <strong class="text-light">{{ Auth::user()->email }}</strong>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('orders.index') }}">
                                        <i class="fa-solid fa-clock-rotate-left me-2 text-info"></i> Đơn hàng của tôi
                                    </a>
                                </li>
                                @if(Auth::user()->role === 'admin')
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                            <i class="fa-solid fa-gauge-high me-2 text-warning"></i> Dashboard Admin
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.orders.index') }}">
                                            <i class="fa-solid fa-receipt me-2 text-success"></i> Quản lý Đơn hàng
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Đăng xuất
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Body Container -->
    <main class="main-content">
        <div class="container">
            <!-- Unverified Email Alert Banner -->
            @auth
                @if(Auth::user()->role !== 'admin' && !Auth::user()->hasVerifiedEmail() && !request()->routeIs('verification.*'))
                    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center justify-content-between shadow-sm mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-triangle-exclamation fs-5 text-warning me-2"></i>
                            <div>
                                <strong>Tài khoản chưa xác thực email:</strong> Vui lòng xác thực email để sử dụng Giỏ hàng & Mua hàng.
                            </div>
                        </div>
                        <a href="{{ route('verification.notice') }}" class="btn btn-sm btn-warning fw-bold ms-3">
                            <i class="fa-solid fa-envelope-circle-check me-1"></i> Xác thực ngay
                        </a>
                    </div>
                @endif
            @endauth

            <!-- Global Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-check fs-5 me-2"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-exclamation fs-5 me-2"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <span class="fw-bold text-light">THÁI SƠN TILES</span> - <span class="text-light text-opacity-75">Hệ thống phân phối gạch ốp lát cao cấp</span>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('welcome') }}" class="text-decoration-none text-light text-opacity-75 hover-cyan">Trang chủ</a>
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-info">Bảng điều khiển</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </footer>

    @auth
        @if(Auth::user()->role !== 'admin')
        <style>
            #chat-box {
                position: fixed;
                bottom: 24px;
                right: 24px;
                z-index: 9999;
            }
            #chat-toggle {
                font-weight: 600;
                padding: 10px 18px;
                border-radius: 50px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                background: linear-gradient(135deg, #54392b 0%, #7d5843 100%);
                border: 1px solid rgba(229, 178, 121, 0.4);
                color: #fff;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            #chat-toggle:hover {
                background: linear-gradient(135deg, #6b4a38 0%, #996d53 100%);
                color: #fff;
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
            #chat-popup {
                width: 360px;
                max-width: calc(100vw - 32px);
                height: 460px;
                border-radius: 16px;
                border: 1px solid #e9e2d8;
                display: none;
                flex-direction: column;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(42, 28, 21, 0.25);
                background: #ffffff;
            }
            #chat-popup .card-header {
                background: linear-gradient(135deg, #2a1c15 0%, #452e23 100%);
                color: #ffffff;
                padding: 12px 16px;
                font-weight: 600;
            }
            #chat-messages {
                flex: 1;
                overflow-y: auto;
                padding: 14px;
                background-color: #fdfbf7;
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            .message-row {
                padding: 8px 12px;
                border-radius: 12px;
                max-width: 85%;
                font-size: 0.88rem;
                word-break: break-word;
                line-height: 1.4;
            }
            .user-msg {
                align-self: flex-end;
                background-color: #54392b;
                color: #ffffff;
                border-bottom-right-radius: 2px;
            }
            .admin-msg {
                align-self: flex-start;
                background-color: #f0e8dc;
                color: #2a1c15;
                border-bottom-left-radius: 2px;
            }
        </style>

        <div id="chat-box">
            <button id="chat-toggle" class="btn rounded-pill shadow position-relative">
                <span>💬 Chat hỗ trợ</span>
                <span id="chat-badge" class="chat-badge-dot d-none">
                    <span class="badge-ping"></span>
                    <span class="badge-num" id="chat-badge-count">1</span>
                </span>
            </button>
            <div id="chat-popup" class="card shadow-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span><i class="fa-solid fa-headset me-1"></i> Hỗ trợ khách hàng</span>
                    <button id="chat-close" class="btn btn-sm btn-light py-0 px-2 fw-bold">✕</button>
                </div>
                <div id="chat-messages" class="card-body">
                    <small class="text-muted text-center d-block">Đang tải lịch sử...</small>
                </div>
                <div class="card-footer bg-white border-top p-2">
                    <div class="input-group">
                        <input type="text" id="chat-input" class="form-control form-control-sm" placeholder="Nhập tin nhắn..." autocomplete="off">
                        <button id="send-btn" class="btn btn-success btn-sm"><i class="fa-solid fa-paper-plane me-1"></i> Gửi</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleBtn = document.getElementById("chat-toggle");
            const chatPopup = document.getElementById("chat-popup");
            const closeBtn = document.getElementById("chat-close");
            const sendBtn = document.getElementById("send-btn");
            const input = document.getElementById("chat-input");
            const chatBox = document.getElementById("chat-messages");
            const chatBadge = document.getElementById("chat-badge");
            const chatBadgeCount = document.getElementById("chat-badge-count");

            if (!toggleBtn) return;

            // --- MỞ / ĐÓNG CHAT ---
            toggleBtn.onclick = () => {
                chatPopup.style.display = "flex";
                toggleBtn.style.display = "none";
                if (chatBadge) chatBadge.classList.add("d-none");
                loadMessages();
            };

            closeBtn.onclick = () => {
                chatPopup.style.display = "none";
                toggleBtn.style.display = "flex";
                checkUnreadCount();
            };

            // --- KIỂM TRA TIN CHƯA ĐỌC ---
            function checkUnreadCount() {
                if (chatPopup.style.display === "flex" || chatPopup.style.display === "block") {
                    return; // Đang mở chat thì không hiện badge
                }
                fetch("{{ route('user.chat.unreadCount') }}")
                    .then(res => res.json())
                    .then(data => {
                        if (data.unread_count > 0) {
                            chatBadge.classList.remove("d-none");
                            chatBadgeCount.textContent = data.unread_count;
                        } else {
                            chatBadge.classList.add("d-none");
                        }
                    })
                    .catch(() => {});
            }

            // --- LOAD TIN NHẮN ---
            function loadMessages() {
                fetch("{{ route('user.chat.messages') }}")
                    .then(res => res.json())
                    .then(messages => {
                        let html = "";
                        if(messages.length === 0) {
                            html = "<div class='text-center text-muted my-auto'><small>Bắt đầu cuộc trò chuyện với Quản trị viên</small></div>";
                        } else {
                            messages.forEach(msg => {
                                const isMe = msg.sender_id == "{{ Auth::id() }}";
                                html += `
                                    <div class="message-row ${isMe ? 'user-msg' : 'admin-msg'}">
                                        <small class="fw-bold d-block" style="font-size: 0.72rem; opacity: 0.85;">${isMe ? 'Bạn' : 'Quản trị viên'}</small>
                                        <span>${msg.content}</span>
                                    </div>
                                `;
                            });
                        }
                        chatBox.innerHTML = html;
                        chatBox.scrollTop = chatBox.scrollHeight;
                    })
                    .catch(err => console.error("Lỗi tải tin nhắn:", err));
            }

            // --- GỬI TIN NHẮN ---
            function sendMessage() {
                let message = input.value.trim();
                if (message === "") return;

                input.disabled = true;
                sendBtn.disabled = true;

                fetch("{{ route('user.chat.send') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ message: message })
                })
                .then(res => res.json())
                .then(data => {
                    input.value = "";
                    input.disabled = false;
                    sendBtn.disabled = false;
                    input.focus();
                    loadMessages();
                })
                .catch(err => {
                    console.error("Lỗi gửi tin:", err);
                    input.disabled = false;
                    sendBtn.disabled = false;
                });
            }

            sendBtn.onclick = sendMessage;

            input.addEventListener("keypress", function(e) {
                if (e.key === "Enter") {
                    sendMessage();
                }
            });

            // Khởi tạo kiểm tra tin nhắn
            checkUnreadCount();

            // --- AUTO REFRESH (3 giây/lần) ---
            setInterval(() => {
                if (chatPopup.style.display === "flex" || chatPopup.style.display === "block") {
                    loadMessages();
                } else {
                    checkUnreadCount();
                }
            }, 3000);
        });
        </script>
        @endif
    @endauth

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
