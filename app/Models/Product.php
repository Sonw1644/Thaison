<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model quản lý thông tin Sản phẩm gạch ốp lát
 */
class Product extends Model
{
    use HasFactory;

    // Danh sách các cột được phép gán dữ liệu hàng loạt (Mass Assignment)
    protected $fillable = [
        'category_id',        // ID danh mục gạch
        'name',               // Tên sản phẩm gạch
        'sku',                // Mã SKU sản phẩm
        'unit',               // Đơn vị tính (Thùng, Hộp, m2, Viên...)
        'price',              // Giá niêm yết
        'sale_price',         // Giá khuyến mãi
        'stock',              // Tổng số lượng tồn kho
        'brand',              // Tên thương hiệu / nhà sản xuất
        'size',               // Kích thước (VD: 800x800 mm)
        'area_per_box',       // Diện tích trên 1 thùng (m2)
        'pieces_per_box',     // Số viên trên 1 thùng
        'surface',            // Bề mặt (Men bóng, Men mờ...)
        'material',           // Chất liệu (Porcelain, Granite, Ceramic...)
        'color_pattern',      // Họa tiết hoa văn
        'origin',             // Xuất xứ (Việt Nam, Ý...)
        'anti_slip',          // Độ chống trơn (R9, R10...)
        'weight_per_box',     // Trọng lượng (kg/thùng)
        'short_description',  // Mô tả ngắn
        'description',        // Mô tả chi tiết
        'thumbnail',          // Ảnh đại diện chính
        'is_featured',        // Đánh dấu sản phẩm nổi bật
        'status',             // Trạng thái kích hoạt hiển thị (Đang bán)
    ];

    // Ép kiểu dữ liệu tự động cho các trường
    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'area_per_box' => 'decimal:3',
        'pieces_per_box' => 'integer',
        'weight_per_box' => 'decimal:2',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Mối quan hệ: Sản phẩm thuộc về 1 Danh mục (Hạng mục gạch)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Mối quan hệ: 1 Sản phẩm gạch có nhiều Biến thể màu sắc & vân gạch
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}
