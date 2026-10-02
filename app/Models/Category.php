<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model quản lý Danh mục (Hạng mục gạch)
 */
class Category extends Model
{
    use HasFactory;

    // Các cột cho phép ghi dữ liệu
    protected $fillable = [
        'name',         // Tên hạng mục gạch
        'description',  // Mô tả chi tiết
        'image',        // Ảnh đại diện danh mục
        'sort_order',   // Thứ tự hiển thị
        'status',       // Trạng thái (Hiển thị / Ẩn)
    ];

    // Ép kiểu dữ liệu
    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Mối quan hệ: 1 Danh mục có nhiều Sản phẩm gạch
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}