<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'province',
        'district',
        'ward',
        'shipping_address',
        'notes',
        'payment_method',
        'payment_status',
        'status',
        'total_amount',
        'shipping_status',
        'ghn_order_code',
        'ghn_total_fee',
        'to_district_id',
        'to_ward_code',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Mối quan hệ: Đơn hàng thuộc về 1 tài khoản (hoặc null nếu mua vãng lai)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mối quan hệ: 1 Đơn hàng có nhiều mục sản phẩm (items)
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Mối quan hệ: 1 Đơn hàng có nhiều giao dịch thanh toán
     */
    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class, 'order_id');
    }

    public function getNameAttribute(): string
    {
        return $this->customer_name ?? '';
    }

    public function getPhoneAttribute(): string
    {
        return $this->customer_phone ?? '';
    }

    public function getTotalPriceAttribute(): float
    {
        return (float) ($this->total_amount ?? 0);
    }

    /**
     * Helper: Tên tiếng Việt của phương thức thanh toán
     */
    public function getPaymentMethodNameAttribute(): string
    {
        return match ($this->payment_method) {
            'cod' => 'Thanh toán khi nhận hàng (COD)',
            'banking' => 'Chuyển khoản Ngân hàng (VietQR)',
            'momo' => 'Ví điện tử MoMo',
            default => 'Khác',
        };
    }

    /**
     * Helper: Tên tiếng Việt của trạng thái đơn hàng
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'completed'                 => 'Đã hoàn thành',
            'shipping', 'delivering'    => 'Đang giao',
            'pending', 'cod_ordered'    => 'Chờ xử lý',
            'cancelled'                 => 'Đã hủy',
            default                     => 'Chờ xử lý',
        };
    }

    /**
     * Helper: Class màu badge cho trạng thái đơn hàng
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'completed'                 => 'bg-success-subtle text-success border border-success-subtle',
            'shipping', 'delivering'    => 'bg-info-subtle text-info-emphasis border border-info-subtle',
            'pending', 'cod_ordered'    => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
            'cancelled'                 => 'bg-danger-subtle text-danger border border-danger-subtle',
            default                     => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
        };
    }

    /**
     * Helper: Tên tiếng Việt của trạng thái thanh toán
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'unpaid' => 'Chưa thanh toán',
            'paid' => 'Đã thanh toán',
            'refunded' => 'Đã hoàn tiền',
            default => 'Chưa rõ',
        };
    }

    /**
     * Helper: Class màu badge cho trạng thái thanh toán
     */
    public function getPaymentStatusBadgeClassAttribute(): string
    {
        return match ($this->payment_status) {
            'unpaid' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
            'paid' => 'bg-success-subtle text-success border border-success-subtle',
            'refunded' => 'bg-secondary-subtle text-secondary',
            default => 'bg-light text-dark',
        };
    }

    /**
     * Địa chỉ giao hàng đầy đủ
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->shipping_address,
            $this->ward,
            $this->district,
            $this->province,
        ]);
        return implode(', ', $parts);
    }
}
