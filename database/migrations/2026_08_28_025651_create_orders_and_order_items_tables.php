<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Bảng Đơn Hàng (orders)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique(); // Mã đơn hàng (VD: TS-20260828-9842)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Thông tin người nhận hàng
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->string('province')->nullable(); // Tỉnh / Thành phố
            $table->string('district')->nullable(); // Quận / Huyện
            $table->string('ward')->nullable(); // Phường / Xã
            $table->text('shipping_address'); // Địa chỉ chi tiết (số nhà, đường)
            $table->text('notes')->nullable(); // Ghi chú đơn hàng

            // Phương thức & Trạng thái thanh toán
            $table->string('payment_method')->default('cod'); // cod, banking
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, refunded
            
            // Trạng thái đơn hàng
            $table->string('status')->default('pending'); // pending (Chờ xử lý), confirmed (Đã xác nhận), shipping (Đang giao), completed (Đã hoàn thành), cancelled (Đã hủy)
            
            // Tổng tiền
            $table->decimal('total_amount', 15, 2)->default(0);

            $table->timestamps();
        });

        // 2. Bảng Chi Tiết Mặt Hàng Trong Đơn (order_items)
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            
            $table->string('product_name');
            $table->string('variant_color_name')->nullable();
            $table->string('thumbnail')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->decimal('subtotal', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
