@extends('layouts.app')

@section('title', 'Xác Thực Email - THÁI SƠN TILES')

@section('styles')
<style>
    .auth-page-wrapper {
        min-height: calc(100vh - 180px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px 15px;
        position: relative;
    }

    .auth-page-wrapper::before {
        content: "";
        position: absolute;
        width: 380px;
        height: 380px;
        background: radial-gradient(circle, rgba(224, 169, 109, 0.18) 0%, rgba(224, 169, 109, 0) 70%);
        top: 10%;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 50%;
        filter: blur(50px);
        pointer-events: none;
        z-index: 0;
    }

    .verify-card {
        width: 100%;
        max-width: 540px;
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid #e7e0d6;
        box-shadow: 0 20px 40px -15px rgba(38, 24, 16, 0.08), 0 0 1px 1px rgba(231, 224, 214, 0.6);
        overflow: hidden;
        position: relative;
        z-index: 1;
        text-align: center;
    }

    .verify-card-header {
        background: linear-gradient(135deg, #2a1c15 0%, #3a261d 50%, #4d3427 100%);
        padding: 36px 28px 28px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }

    .verify-icon-box {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        background: rgba(224, 169, 109, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(224, 169, 109, 0.35);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #e0a96d;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        margin-bottom: 16px;
        animation: pulse-glow 2s infinite ease-in-out;
    }

    @keyframes pulse-glow {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .verify-body {
        padding: 32px 28px;
    }

    .user-email-badge {
        display: inline-block;
        background: #f7f4ef;
        border: 1px solid #e7e0d6;
        color: #261810;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 6px 16px;
        border-radius: 50px;
        margin: 12px 0 20px;
        word-break: break-all;
    }

    .btn-verify-resend {
        background: linear-gradient(135deg, #261810 0%, #3d281d 50%, #4a3528 100%);
        border: 1px solid #6c4e31;
        border-radius: 12px;
        color: #ffffff;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 13px 24px;
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 8px 20px -4px rgba(38, 24, 16, 0.35);
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .btn-verify-resend:hover {
        background: linear-gradient(135deg, #3d281d 0%, #6c4e31 100%);
        border-color: #d4a373;
        transform: translateY(-2px);
        box-shadow: 0 10px 24px -4px rgba(38, 24, 16, 0.45);
        color: #ffffff;
    }

    .btn-logout-link {
        background: none;
        border: none;
        color: #ef4444;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        padding: 0;
        text-decoration: none;
        transition: opacity 0.2s;
    }

    .btn-logout-link:hover {
        opacity: 0.8;
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div class="auth-page-wrapper">
    <div class="verify-card">
        <!-- Header -->
        <div class="verify-card-header">
            <div class="verify-icon-box">
                <i class="fa-solid fa-envelope-circle-check"></i>
            </div>
            <h4 class="fw-bold mb-1" style="letter-spacing: -0.3px;">Xác Thực Địa Chỉ <span style="color: #e0a96d;">Email</span></h4>
            <p class="text-light text-opacity-75 small mb-0">Bước cuối cùng để kích hoạt toàn bộ tính năng tài khoản</p>
        </div>

        <!-- Body -->
        <div class="verify-body">
            @if (session('message'))
                <div class="alert alert-success d-flex align-items-center mb-4 text-start" role="alert">
                    <i class="fa-solid fa-circle-check fs-5 me-2 flex-shrink-0"></i>
                    <div>{{ session('message') }}</div>
                </div>
            @endif

            <p class="text-secondary mb-2" style="font-size: 0.95rem; line-height: 1.6;">
                Cảm ơn bạn đã đăng ký! Hệ thống đã gửi một đường dẫn xác thực đến địa chỉ email:
            </p>

            @if(Auth::check())
                <div class="user-email-badge">
                    <i class="fa-regular fa-envelope me-1 text-primary"></i> {{ Auth::user()->email }}
                </div>
            @endif

            <p class="text-muted small mb-4">
                Vui lòng kiểm tra hộp thư đến (hoặc thư mục <strong>Spam / Rác</strong>) và nhấn vào liên kết xác nhận để hoàn tất.
            </p>

            <!-- Form gửi lại email xác thực -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-verify-resend">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Gửi lại email xác thực</span>
                </button>
            </form>

            <div class="d-flex justify-content-between align-items-center pt-4 mt-4 border-top">
                <a href="{{ route('welcome') }}" class="text-decoration-none text-muted small">
                    <i class="fa-solid fa-house me-1"></i> Trang chủ
                </a>

                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-logout-link">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
