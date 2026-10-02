<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// 1. Controllers Phân Hệ Khách Hàng (User)
use App\Http\Controllers\User\WelcomeController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\User\GHNController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController as UserOrderController;
use App\Http\Controllers\User\MomoController;
use App\Http\Controllers\User\ChatController as UserChatController;

// 2. Controllers Phân Hệ Xác Thực
use App\Http\Controllers\Auth\AuthController;

// 3. Controllers Phân Hệ Quản Trị (Admin)
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\Admin\FinanceController;

// 1. KHU VỰC CÔNG KHAI & KHÁCH HÀNG (User)
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/products', [WelcomeController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [UserProductController::class, 'show_normal'])->name('products.show');

// GHN API Routes (Tỉnh/Thành, Quận/Huyện, Phường/Xã, Tính Phí)
Route::prefix('locations')->name('locations.')->group(function () {
    Route::get('/provinces', [GHNController::class, 'getProvinces'])->name('provinces');
    Route::get('/districts/{provinceId}', [GHNController::class, 'getDistricts'])->name('districts');
    Route::get('/wards/{districtId}', [GHNController::class, 'getWards'])->name('wards');
    Route::post('/calculate-fee', [GHNController::class, 'getShippingFee'])->name('fee');
});

// Cổng thanh toán MoMo: Webhooks & Callbacks (Không áp dụng middleware auth vì server MoMo gọi sang)
Route::post('/payment/momo/ipn', [MomoController::class, 'ipn'])->name('payment.momo.ipn');
Route::get('/payment/momo/callback', [MomoController::class, 'callback'])->name('payment.momo.callback');
Route::get('/user/payment/momo/callback', [MomoController::class, 'callback'])->name('user.payment.momo.callback');

// 2. TÍNH NĂNG GIỎ HÀNG, ĐẶT HÀNG & QUẢN LÝ ĐƠN HÀNG (Yêu cầu đăng nhập & đã xác thực email)
Route::middleware(['auth', 'verified'])->group(function () {
    // Giỏ hàng
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Đặt hàng & Thanh toán (Checkout)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order_code}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/orders/{order_code}/confirm-payment', [CheckoutController::class, 'confirmPayment'])->name('orders.confirmPayment');
    Route::get('/orders/{order_code}/check-status', [CheckoutController::class, 'checkStatus'])->name('orders.checkStatus');

    // Thanh toán qua MoMo
    Route::get('/orders/{order}/start-momo', [MomoController::class, 'start'])->name('orders.momo.start');
    Route::get('/orders/{order}/pay/momo', [MomoController::class, 'payAgain'])->name('orders.momo.pay');

    // Lịch sử & Chi tiết đơn hàng của khách hàng
    Route::get('/my-orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{order_code}', [UserOrderController::class, 'show'])->name('orders.show');
    Route::post('/my-orders/{order}/cancel', [UserOrderController::class, 'cancel'])->name('orders.cancel');
});

// 3. XÁC THỰC TÀI KHOẢN (Auth)
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// 4. XÁC THỰC EMAIL (Email Verification)
// Hiển thị trang thông báo yêu cầu xác thực email
Route::get('/email/verify', function () {
    if (auth()->user() && auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Xử lý link xác nhận khi người dùng nhấn vào liên kết trong email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('welcome')->with('success', 'Xác thực email thành công! Chào mừng bạn.');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Gửi lại email xác thực
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Đã gửi lại liên kết xác thực vào hộp thư email của bạn!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Livechat Phân Hệ Khách Hàng (User)
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::post('/chat/send', [UserChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/messages', [UserChatController::class, 'getMessages'])->name('chat.messages');
    Route::get('/chat/unread-count', [UserChatController::class, 'getUnreadCount'])->name('chat.unreadCount');
});

// 5. KHU VỰC QUẢN TRỊ ADMIN (Admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Quản lý sản phẩm (Thêm, Sửa, Xóa)
    Route::resource('products', AdminProductController::class);

    // Quản lý danh mục (Thêm, Sửa, Xóa)
    Route::resource('categories', AdminCategoryController::class);

    // Quản lý đơn hàng (Xem danh sách, Chi tiết, Cập nhật trạng thái)
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{order}/confirm-delivered', [AdminOrderController::class, 'confirmDeliveredSingle'])->name('orders.confirmDeliveredSingle');
    Route::post('/orders/confirm-delivered-bulk', [AdminOrderController::class, 'confirmDeliveredBulk'])->name('orders.confirmDeliveredBulk');

    // Quản lý người dùng (Thêm, Xem, Sửa, Xóa)
    Route::resource('users', AdminUserController::class);

    // Báo cáo doanh thu & Biểu đồ thống kê
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/charts', [AdminReportController::class, 'charts'])->name('reports.charts');

    // Livechat Admin
    Route::get('/chat/users', [AdminChatController::class, 'getUsers'])->name('chat.users');
    Route::get('/chat/messages/{userId}', [AdminChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/send', [AdminChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/unread-count', [AdminChatController::class, 'getUnreadCount'])->name('chat.unreadCount');

    // Quản lý Tài chính & Giao dịch (Finance)
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::get('/finance/transactions', [FinanceController::class, 'transactions'])->name('finance.transactions');
    Route::patch('/finance/{order}/status', [FinanceController::class, 'updateStatus'])->name('finance.update-status');
});
