<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model quản lý Biến thể màu sắc & Vân gạch của sản phẩm
 */
class ProductVariant extends Model
{
    use HasFactory;

    // Các cột cho phép ghi dữ liệu
    protected $fillable = [
        'product_id',   // ID sản phẩm cha
        'color_code',   // Mã màu hex (VD: #787878)
        'color_name',   // Tên màu / Vân gạch (VD: Xám bê tông)
        'sku',          // Mã SKU riêng của biến thể
        'stock',        // Số lượng tồn kho của màu này
        'image',        // Ảnh chụp thực tế của màu này
    ];

    // Ép kiểu dữ liệu
    protected $casts = [
        'stock' => 'integer',
    ];

    /**
     * Mối quan hệ: Biến thể màu sắc thuộc về 1 Sản phẩm gạch
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
