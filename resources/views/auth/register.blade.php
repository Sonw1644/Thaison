@extends('layouts.app')

@section('title', 'Đăng Ký Tài Khoản - THÁI SƠN TILES')

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

    /* Ambient Background Glow */
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

    .auth-card {
        width: 100%;
        max-width: 520px;
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid #e7e0d6;
        box-shadow: 0 20px 40px -15px rgba(38, 24, 16, 0.08), 0 0 1px 1px rgba(231, 224, 214, 0.6);
        overflow: hidden;
        position: relative;
        z-index: 1;
    }

    /* Card Header Gradient */
    .auth-card-header {
        background: linear-gradient(135deg, #2a1c15 0%, #3a261d 50%, #4d3427 100%);
        padding: 36px 28px 28px;
        text-align: center;
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }

    .auth-card-header::after {
        content: "";
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(224, 169, 109, 0.12) 1px, transparent 1px);
        background-size: 16px 16px;
        pointer-events: none;
    }

    .auth-logo-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: rgba(224, 169, 109, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(224, 169, 109, 0.35);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        color: #e0a96d;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        margin-bottom: 12px;
    }

    /* Inputs */
    .custom-field-group {
        margin-bottom: 16px;
    }

    .custom-field-label {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #4a3528;
        margin-bottom: 6px;
        display: block;
    }

    .input-box {
        display: flex;
        align-items: center;
        background: #fbf9f6;
        border: 1.5px solid #e7e0d6;
        border-radius: 12px;
        padding: 0 12px;
        transition: all 0.2s ease;
    }

    .input-box:focus-within {
        background: #ffffff;
        border-color: #c48b52;
        box-shadow: 0 0 0 3.5px rgba(196, 139, 82, 0.18);
    }

    .input-box.has-error {
        border-color: #ef4444;
        background: #fff5f5;
    }

    .input-box-icon {
        color: #8b7970;
        font-size: 1rem;
        margin-right: 10px;
        transition: color 0.2s ease;
    }

    .input-box:focus-within .input-box-icon {
        color: #c48b52;
    }

    .input-box input {
        border: none;
        background: transparent;
        width: 100%;
        padding: 12px 0;
        font-size: 0.95rem;
        font-weight: 500;
        color: #261810;
        outline: none;
    }

    .input-box input::placeholder {
        color: #a8a29e;
        font-weight: 400;
    }

    .btn-toggle-eye {
        background: transparent;
        border: none;
        color: #8b7970;
        padding: 4px 6px;
        cursor: pointer;
        transition: color 0.2s;
    }

    .btn-toggle-eye:hover {
        color: #c48b52;
    }

    .match-feedback {
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 4px;
        display: none;
    }

    /* Submit Button */
    .btn-auth-action {
        background: linear-gradient(135deg, #261810 0%, #3d281d 50%, #4a3528 100%);
        border: 1px solid #6c4e31;
        border-radius: 12px;
        color: #ffffff;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 13px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 8px 20px -4px rgba(38, 24, 16, 0.35);
        transition: all 0.25s ease;
    }

    .btn-auth-action:hover {
        background: linear-gradient(135deg, #3d281d 0%, #6c4e31 100%);
        border-color: #d4a373;
        transform: translateY(-2px);
        box-shadow: 0 10px 24px -4px rgba(38, 24, 16, 0.45);
        color: #ffffff;
    }

    .btn-auth-action:active {
        transform: translateY(0);
    }
</style>
@endsection

@section('content')
<div class="auth-page-wrapper">
    <div class="auth-card">
        <!-- Header -->
        <div class="auth-card-header">
            <h4 class="fw-bold mb-1" style="font-weight: 800; letter-spacing: -0.3px;">Tạo Tài <span style="color: #e0a96d;">Khoản</span></h4>
            <p class="text-light text-opacity-75 small mb-0">Đăng ký thành viên để khám phá sản phẩm & ưu đãi</p>
        </div>

        <!-- Body -->
        <div class="p-4 p-sm-4">
            <!-- Form -->
            <form action="{{ url('register') }}" method="POST" id="registerForm">
                @csrf

                <!-- Name -->
                <div class="custom-field-group">
                    <label class="custom-field-label">Họ và Tên</label>
                    <div class="input-box @error('name') has-error @enderror">
                        <i class="fa-regular fa-user input-box-icon"></i>
                        <input type="text" name="name" 
                               placeholder="Ví dụ: Nguyễn Văn A" 
                               value="{{ old('name') }}" 
                               required autofocus>
                    </div>
                    @error('name')
                        <div class="text-danger small mt-1 ps-1">
                            <i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="custom-field-group">
                    <label class="custom-field-label">Địa chỉ Email</label>
                    <div class="input-box @error('email') has-error @enderror">
                        <i class="fa-regular fa-envelope input-box-icon"></i>
                        <input type="email" name="email" 
                               placeholder="nguyenvana@gmail.com" 
                               value="{{ old('email') }}" 
                               required>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1 ps-1">
                            <i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password & Confirm -->
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="custom-field-group">
                            <label class="custom-field-label">Mật khẩu </label>
                            <div class="input-box @error('password') has-error @enderror">
                                <i class="fa-solid fa-lock input-box-icon"></i>
                                <input type="password" name="password" id="regPassword" 
                                       placeholder="••••••••" 
                                       required>
                                <button type="button" class="btn-toggle-eye" id="toggleRegPasswordBtn" title="Hiện/ẩn mật khẩu">
                                    <i class="fa-regular fa-eye" id="toggleRegPasswordIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1 ps-1">
                                    <i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="custom-field-group">
                            <label class="custom-field-label">Xác nhận mật khẩu</label>
                            <div class="input-box" id="confirmInputBox">
                                <i class="fa-solid fa-shield-halved input-box-icon"></i>
                                <input type="password" name="password_confirmation" id="regPasswordConfirm" 
                                       placeholder="••••••••" 
                                       required>
                            </div>
                            <div class="match-feedback" id="matchFeedback"></div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-auth-action mt-2">
                    <i class="fa-solid fa-user-check"></i>
                    <span>ĐĂNG KÝ TÀI KHOẢN</span>
                </button>
            </form>

            <!-- Footer Links -->
            <div class="text-center pt-3 mt-3 border-top">
                <p class="small text-muted mb-1">
                    Đã có tài khoản? 
                    <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Đăng nhập ngay</a>
                </p>
                <p class="small text-muted mb-0">
                    <a href="{{ route('welcome') }}" class="text-secondary text-decoration-none">
                        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại trang chủ
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle Password Visibility
    const toggleRegPasswordBtn = document.getElementById('toggleRegPasswordBtn');
    const regPassword = document.getElementById('regPassword');
    const regPasswordConfirm = document.getElementById('regPasswordConfirm');
    const toggleRegPasswordIcon = document.getElementById('toggleRegPasswordIcon');

    if (toggleRegPasswordBtn && regPassword && regPasswordConfirm && toggleRegPasswordIcon) {
        toggleRegPasswordBtn.addEventListener('click', function () {
            const isPassword = regPassword.type === 'password';
            regPassword.type = isPassword ? 'text' : 'password';
            regPasswordConfirm.type = isPassword ? 'text' : 'password';
            
            if (isPassword) {
                toggleRegPasswordIcon.classList.remove('fa-eye');
                toggleRegPasswordIcon.classList.add('fa-eye-slash');
            } else {
                toggleRegPasswordIcon.classList.remove('fa-eye-slash');
                toggleRegPasswordIcon.classList.add('fa-eye');
            }
        });
    }

    // Password Matching
    const matchFeedback = document.getElementById('matchFeedback');
    const confirmInputBox = document.getElementById('confirmInputBox');

    function checkMatch() {
        if (!regPasswordConfirm || !matchFeedback || !confirmInputBox || !regPassword) return;
        const pass = regPassword.value;
        const confirm = regPasswordConfirm.value;

        if (!confirm) {
            matchFeedback.style.display = 'none';
            confirmInputBox.style.borderColor = '#e2e8f0';
            return;
        }

        matchFeedback.style.display = 'block';
        if (pass === confirm) {
            matchFeedback.innerHTML = '<span class="text-success"><i class="fa-solid fa-circle-check me-1"></i> Mật khẩu khớp</span>';
            confirmInputBox.style.borderColor = '#10b981';
        } else {
            matchFeedback.innerHTML = '<span class="text-danger"><i class="fa-solid fa-circle-xmark me-1"></i> Chưa khớp</span>';
            confirmInputBox.style.borderColor = '#ef4444';
        }
    }

    if (regPasswordConfirm) {
        regPasswordConfirm.addEventListener('input', checkMatch);
    }
</script>
@endsection