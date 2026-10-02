<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('status', true);

        // 1. Tìm kiếm từ khóa (Tên gạch, mã SKU, thương hiệu, hoa văn...)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('color_pattern', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // 2. Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 3. Lọc theo thương hiệu
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // 4. Lọc theo kích thước
        if ($request->filled('size')) {
            $query->where('size', $request->size);
        }

        // 5. Lọc theo bề mặt (Men bóng, Men mờ, Men sần...)
        if ($request->filled('surface')) {
            $query->where('surface', 'like', "%{$request->surface}%");
        }

        // 6. Lọc theo khoảng giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float)$request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float)$request->max_price);
        }

        // 7. Sắp xếp
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stock', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(8)->withQueryString();

        // Danh sách dữ liệu cho bộ lọc
        $categories = Category::where('status', true)->withCount('products')->get();
        $brands = Product::where('status', true)->whereNotNull('brand')->distinct()->pluck('brand');
        $sizes = Product::where('status', true)->whereNotNull('size')->distinct()->pluck('size');
        $surfaces = ['Men bóng', 'Men mờ', 'Men sần vân gỗ', 'Nhám chống trượt', 'Men vi tinh'];

        // Đếm số lượng bộ lọc đang được kích hoạt
        $activeFiltersCount = 0;
        if ($request->filled('search')) $activeFiltersCount++;
        if ($request->filled('category_id')) $activeFiltersCount++;
        if ($request->filled('brand')) $activeFiltersCount++;
        if ($request->filled('size')) $activeFiltersCount++;
        if ($request->filled('surface')) $activeFiltersCount++;
        if ($request->filled('min_price') || $request->filled('max_price')) $activeFiltersCount++;
        if ($request->filled('sort') && $request->sort !== 'latest') $activeFiltersCount++;

        return view('welcome', compact(
            'products',
            'categories',
            'brands',
            'sizes',
            'surfaces',
            'activeFiltersCount'
        ));
    }
}
