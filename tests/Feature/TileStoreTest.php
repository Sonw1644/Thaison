<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TileStoreTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\TileStoreSeeder::class);
        $this->adminUser = User::where('role', 'admin')->first();
    }

    public function test_dashboard_page_is_accessible()
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Bảng Điều Khiển Quản Trị');
        $response->assertSee('THÁI SƠN');
    }

    public function test_categories_page_is_accessible()
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/categories');
        $response->assertStatus(200);
        $response->assertSee('Quản Lý Hạng Mục Gạch');
    }

    public function test_products_page_is_accessible()
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/products');
        $response->assertStatus(200);
        $response->assertSee('Quản Lý Sản Phẩm Gạch Ốp Lát');
    }

    public function test_products_create_page_is_accessible()
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/products/create');
        $response->assertStatus(200);
        $response->assertSee('Thêm Mới Sản Phẩm Gạch Ốp Lát');
    }

    public function test_products_edit_page_is_accessible()
    {
        $product = Product::first();
        $this->assertNotNull($product);
        $response = $this->actingAs($this->adminUser)->get("/admin/products/{$product->id}/edit");
        $response->assertStatus(200);
        $response->assertSee('Chỉnh Sửa Sản Phẩm');
    }

    public function test_can_create_product()
    {
        $category = Category::first();
        $response = $this->actingAs($this->adminUser)->post('/admin/products', [
            'name' => 'Gạch lát sân cao cấp',
            'sku' => 'TEST-SKU-999',
            'category_id' => $category->id,
            'brand' => 'Prime Group',
            'unit' => 'Thùng (Hộp)',
            'price' => 350000,
            'stock' => 100,
            'size' => '600x600 mm',
            'area_per_box' => 1.44,
            'pieces_per_box' => 4,
            'surface' => 'Men mờ (Matt)',
            'material' => 'Porcelain',
            'color_pattern' => 'Xám xi măng',
            'origin' => 'Việt Nam',
            'anti_slip' => 'R10',
            'weight_per_box' => 30,
            'is_featured' => 1,
            'status' => 1,
            'variants' => [
                [
                    'color_code' => '#111111',
                    'color_name' => 'Xám Đen',
                    'sku' => 'SKU-BLK',
                    'stock' => 50,
                ]
            ]
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', ['sku' => 'TEST-SKU-999']);
        $this->assertDatabaseHas('product_variants', ['color_name' => 'Xám Đen']);
    }
}
