<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng ký
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Xử lý đăng ký tài khoản khách hàng
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được đăng ký tài khoản.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        try {
            Log::info('Đăng ký tài khoản khách hàng: ' . $request->email);

            // Mặc định quyền khi đăng ký là 'customer'
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer',
            ]);

            // Gửi email xác thực tài khoản
            $user->sendEmailVerificationNotification();

            // Đăng nhập tự động ngay sau khi đăng ký thành công cho khách hàng
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('verification.notice')
                             ->with('success', 'Đăng ký tài khoản thành công! Vui lòng kiểm tra hộp thư email để xác thực tài khoản của bạn.');
        } catch (Exception $e) {
            Log::error('Đăng ký thất bại: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Đăng ký không thành công. Vui lòng thử lại!');
        }
    }

    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

   
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            // Phân quyền đăng nhập
            if (Auth::user()->role === 'admin') {
                // Luôn chuyển hướng thẳng vào Bảng điều khiển (Dashboard) của Admin khi đăng nhập
                session()->forget('url.intended');
                return redirect()->route('admin.dashboard')
                                 ->with('success', 'Đăng nhập thành công với quyền Quản trị viên (Admin)!');
            }

            // Người dùng / Khách hàng bình thường
            return redirect()->intended(route('welcome'))
                             ->with('success', 'Đăng nhập thành công! Chào mừng quý khách ' . Auth::user()->name . '.');
        }

        return redirect()->back()->withInput($request->only('email'))->with('error', 'Email hoặc mật khẩu không chính xác.');
    }

    /**
     * Đăng xuất người dùng
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')->with('success', 'Đã đăng xuất tài khoản thành công.');
    }
}
