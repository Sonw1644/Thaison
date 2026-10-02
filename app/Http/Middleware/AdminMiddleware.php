<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Kiểm tra đã đăng nhập và có quyền 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // 2. Nếu không phải admin -> chuyển hướng về trang khách hàng với thông báo
        return redirect()->route('welcome')->with('error', 'Bạn không có quyền truy cập vào khu vực Quản trị Admin!');
    }
}