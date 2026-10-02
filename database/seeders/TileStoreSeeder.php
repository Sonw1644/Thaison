<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class TileStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\User::truncate();
        ProductVariant::truncate();
        Product::truncate();
        Category::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // Tạo tài khoản Admin mặc định
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $categories = [
            [
                'name' => 'Gạch lát nền',
                'description' => 'Các dòng gạch lát nền phòng khách, phòng ngủ...',
                'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=150&auto=format&fit=crop&q=80',
                'sort_order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Gạch lát nền 60×60 cm',
                'description' => 'Chưa có mô tả',
                'image' => 'https://images.unsplash.com/photo-1600565193348-f74bd3c7ccdf?w=150&auto=format&fit=crop&q=80',
                'sort_order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Gạch ốp tường 30×60 cm',
                'description' => 'Chưa có mô tả',
                'image' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=150&auto=format&fit=crop&q=80',
                'sort_order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Gạch giả gỗ 15×80 cm',
                'description' => 'Chưa có mô tả',
                'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=150&auto=format&fit=crop&q=80',
                'sort_order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Gạch vân đá Calacatta',
                'description' => 'Chưa có mô tả',
                'image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=150&auto=format&fit=crop&q=80',
                'sort_order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Gạch sân vườn 40×40 cm',
                'description' => 'Chưa có mô tả',
                'image' => 'https://images.unsplash.com/photo-1590381105924-c72589b9ef3f?w=150&auto=format&fit=crop&q=80',
                'sort_order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Gạch mosaic hồ bơi',
                'description' => 'Chưa có mô tả',
                'image' => 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?w=150&auto=format&fit=crop&q=80',
                'sort_order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Gạch lát nền 80×80 cm',
                'description' => 'Chưa có mô tả',
                'image' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=150&auto=format&fit=crop&q=80',
                'sort_order' => 2,
                'status' => true,
            ],
            [
                'name' => 'Gạch ốp tường',
                'description' => 'Gạch ốp tường phòng tắm, phòng bếp, phòng khách...',
                'image' => 'https://images.unsplash.com/photo-1552321554-5fefe8c9ef14?w=150&auto=format&fit=crop&q=80',
                'sort_order' => 2,
                'status' => true,
            ],
            [
                'name' => 'Gạch ốp tường 40×80 cm',
                'description' => 'Chưa có mô tả',
                'image' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=150&auto=format&fit=crop&q=80',
                'sort_order' => 2,
                'status' => true,
            ],
            [
                'name' => 'Gạch bông cổ điển',
                'description' => 'Gạch hoa văn cổ điển trang trí',
                'image' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=150&auto=format&fit=crop&q=80',
                'sort_order' => 2,
                'status' => true,
            ],
            [
                'name' => 'Gạch thẻ ốp trang trí',
                'description' => 'Chưa có mô tả',
                'image' => 'https://images.unsplash.com/photo-1584622781564-1d987f7333c1?w=150&auto=format&fit=crop&q=80',
                'sort_order' => 3,
                'status' => true,
            ]
        ];

        $createdCategories = [];
        foreach ($categories as $cat) {
            $createdCategories[$cat['name']] = Category::create($cat);
        }

        // Create sample tile products
        $productsData = [
            [
                'name' => 'Gạch cao cấp Catalan Xám bê tông 800×800 mm',
                'sku' => 'GVH-WN2-0016',
                'category' => 'Gạch lát nền',
                'brand' => 'Catalan',
                'unit' => 'Thùng (Hộp)',
                'price' => 320000,
                'sale_price' => 295000,
                'stock' => 309,
                'size' => '800×800 mm',
                'area_per_box' => 1.920,
                'pieces_per_box' => 3,
                'surface' => 'Men mờ (Matt)',
                'material' => 'Porcelain',
                'color_pattern' => 'Xám bê tông / Đa sắc',
                'origin' => 'Việt Nam',
                'anti_slip' => 'R9',
                'weight_per_box' => 45.0,
                'short_description' => 'Gạch lát nền Catalan tông xám bê tông hiện đại phong cách công nghiệp tối giản.',
                'description' => '<p>Gạch lát nền Catalan kích thước 800x800mm chất liệu Porcelain xương bán sứ siêu bền, bề mặt phủ men mờ chống trơn trượt tối đa.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=400&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'status' => true,
                'variants' => [
                    ['color_code' => '#787878', 'color_name' => 'Xám Bê Tông (Matt Grey)', 'sku' => 'GVH-WN2-GRY', 'stock' => 120, 'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=150&auto=format&fit=crop&q=80'],
                    ['color_code' => '#333333', 'color_name' => 'Xám Đậm (Dark Concrete)', 'sku' => 'GVH-WN2-BLK', 'stock' => 95, 'image' => 'https://images.unsplash.com/photo-1600565193348-f74bd3c7ccdf?w=150&auto=format&fit=crop&q=80'],
                    ['color_code' => '#d4d4d8', 'color_name' => 'Xám Khói (Light Grey)', 'sku' => 'GVH-WN2-LGT', 'stock' => 94, 'image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=150&auto=format&fit=crop&q=80'],
                ]
            ],
            [
                'name' => 'Gạch cao cấp Taicera Vàng kem mộc 600×1200 mm',
                'sku' => 'GVH-UBE-0017',
                'category' => 'Gạch bông cổ điển',
                'brand' => 'Taicera',
                'unit' => 'Thùng (Hộp)',
                'price' => 300000,
                'sale_price' => null,
                'stock' => 278,
                'size' => '600×1200 mm',
                'area_per_box' => 1.440,
                'pieces_per_box' => 2,
                'surface' => 'Men bóng',
                'material' => 'Granite',
                'color_pattern' => 'Vàng kem mộc mạc',
                'origin' => 'Đài Loan / VN',
                'anti_slip' => 'R9',
                'weight_per_box' => 38.5,
                'short_description' => 'Gạch ốp lát khổ lớn Taicera vàng kem ấm cúng sang trọng.',
                'description' => '<p>Gạch Granite đồng chất Taicera kích thước 60x120 sang trọng cho sảnh lớn, phòng khách biệt thự.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1600565193348-f74bd3c7ccdf?w=400&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'status' => true,
                'variants' => [
                    ['color_code' => '#fef08a', 'color_name' => 'Vàng Kem Nhạt', 'sku' => 'GVH-UBE-YEL1', 'stock' => 80, 'image' => null],
                    ['color_code' => '#fde047', 'color_name' => 'Vàng Mộc Đậm', 'sku' => 'GVH-UBE-YEL2', 'stock' => 70, 'image' => null],
                    ['color_code' => '#e2e8f0', 'color_name' => 'Trắng Ánh Vàng', 'sku' => 'GVH-UBE-WHT', 'stock' => 68, 'image' => null],
                    ['color_code' => '#fed7aa', 'color_name' => 'Cam Đất Nhẹ', 'sku' => 'GVH-UBE-ORG', 'stock' => 60, 'image' => null],
                ]
            ],
            [
                'name' => 'Gạch cao cấp Prime Group Ghi xám đậm 300×600 mm',
                'sku' => 'GVH-37U-0018',
                'category' => 'Gạch ốp tường',
                'brand' => 'Prime Group',
                'unit' => 'Thùng (Hộp)',
                'price' => 325000,
                'sale_price' => 285000,
                'stock' => 341,
                'size' => '300×600 mm',
                'area_per_box' => 1.440,
                'pieces_per_box' => 8,
                'surface' => 'Men mờ (Matt)',
                'material' => 'Ceramic',
                'color_pattern' => 'Ghi xám vân đá',
                'origin' => 'Việt Nam',
                'anti_slip' => 'R10',
                'weight_per_box' => 26.0,
                'short_description' => 'Gạch ốp tường Prime 30x60 ghi xám sang trọng cho phòng tắm và bếp.',
                'description' => '<p>Gạch ốp tường bề mặt mịn, lau chùi dễ dàng, chống bám bẩn và chống ố nước hiệu quả.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=400&auto=format&fit=crop&q=80',
                'is_featured' => false,
                'status' => true,
                'variants' => [
                    ['color_code' => '#475569', 'color_name' => 'Ghi Xám Đậm', 'sku' => 'GVH-37U-DGRY', 'stock' => 90, 'image' => null],
                    ['color_code' => '#94a3b8', 'color_name' => 'Ghi Xám Sáng', 'sku' => 'GVH-37U-LGRY', 'stock' => 85, 'image' => null],
                    ['color_code' => '#ffffff', 'color_name' => 'Trắng Điểm Vân Ghi', 'sku' => 'GVH-37U-WHT', 'stock' => 86, 'image' => null],
                    ['color_code' => '#1e293b', 'color_name' => 'Đen Xám Đá Muối', 'sku' => 'GVH-37U-BLK', 'stock' => 80, 'image' => null],
                ]
            ],
            [
                'name' => 'Gạch cao cấp Viglacera Nâu gỗ mun 200×1200 mm',
                'sku' => 'GVH-4LO-0019',
                'category' => 'Gạch thẻ ốp trang trí',
                'brand' => 'Viglacera',
                'unit' => 'Thùng (Hộp)',
                'price' => 350000,
                'sale_price' => null,
                'stock' => 143,
                'size' => '200×1200 mm',
                'area_per_box' => 1.440,
                'pieces_per_box' => 6,
                'surface' => 'Men sần vân gỗ tự nhiên',
                'material' => 'Porcelain',
                'color_pattern' => 'Vân Gỗ Mun',
                'origin' => 'Việt Nam',
                'anti_slip' => 'R10',
                'weight_per_box' => 32.0,
                'short_description' => 'Gạch thanh giả gỗ Viglacera sang trọng ấm áp như gỗ thật.',
                'description' => '<p>Gạch vân gỗ Viglacera kháng nước 100%, không mối mọt cong vênh, thích hợp lát phòng ngủ và ban công.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=400&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'status' => true,
                'variants' => [
                    ['color_code' => '#451a03', 'color_name' => 'Gỗ Mun Đen', 'sku' => 'GVH-4LO-MUN', 'stock' => 50, 'image' => null],
                    ['color_code' => '#78350f', 'color_name' => 'Gỗ Óc Chó (Walnut)', 'sku' => 'GVH-4LO-WAL', 'stock' => 50, 'image' => null],
                    ['color_code' => '#b45309', 'color_name' => 'Gỗ Sồi Vàng (Oak)', 'sku' => 'GVH-4LO-OAK', 'stock' => 43, 'image' => null],
                ]
            ],
        ];

        foreach ($productsData as $pData) {
            $catName = $pData['category'];
            $category = $createdCategories[$catName] ?? Category::first();
            
            $product = Product::create([
                'category_id' => $category->id,
                'name' => $pData['name'],
                'sku' => $pData['sku'],
                'unit' => $pData['unit'],
                'price' => $pData['price'],
                'sale_price' => $pData['sale_price'],
                'stock' => $pData['stock'],
                'brand' => $pData['brand'],
                'size' => $pData['size'],
                'area_per_box' => $pData['area_per_box'],
                'pieces_per_box' => $pData['pieces_per_box'],
                'surface' => $pData['surface'],
                'material' => $pData['material'],
                'color_pattern' => $pData['color_pattern'],
                'origin' => $pData['origin'],
                'anti_slip' => $pData['anti_slip'],
                'weight_per_box' => $pData['weight_per_box'],
                'short_description' => $pData['short_description'],
                'description' => $pData['description'],
                'thumbnail' => $pData['thumbnail'],
                'is_featured' => $pData['is_featured'],
                'status' => $pData['status'],
            ]);

            if (!empty($pData['variants'])) {
                foreach ($pData['variants'] as $v) {
                    $product->variants()->create($v);
                }
            }
        }
    }
}
