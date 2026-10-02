<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants'])->latest();

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('sku', 'like', "%{$keyword}%")
                  ->orWhere('brand', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('stock_status')) {
            $stockStatus = $request->input('stock_status');
            if ($stockStatus === 'in_stock') {
                $query->where('stock', '>', 0); 
            } elseif ($stockStatus === 'out_of_stock') {
                $query->where('stock', '<=', 0); 
            } elseif ($stockStatus === 'low_stock') {
                $query->where('stock', '>', 0)->where('stock', '<=', 50);
            }
        }

        // Đếm tổng số sản phẩm hiện có trong kho
        $totalCount = Product::count();

        // Phân trang 
        $products = $query->paginate(15)->withQueryString();

        // Lấy tất cả danh mục để hiển thị trên ô chọn lọc
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'totalCount'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // 1. Kiểm tra tính hợp lệ của dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'category_id' => 'required|exists:categories,id', 
            'brand' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'size' => 'nullable|string|max:100',
            'area_per_box' => 'nullable|numeric|min:0',
            'pieces_per_box' => 'nullable|integer|min:0',
            'surface' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'color_pattern' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'anti_slip' => 'nullable|string|max:50',
            'weight_per_box' => 'nullable|numeric|min:0',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = $validated;
        $data['unit'] = $request->input('unit', 'Thùng (Hộp)');
        $data['is_featured'] = $request->has('is_featured') ? true : false;
        $data['status'] = $request->has('status') ? true : false;

        // 2. Xử lý tải lên ảnh đại diện chính nếu có chọn file
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('products', 'public');
            $data['thumbnail'] = '/storage/' . $path;
        }

        // 3. Tạo bản ghi sản phẩm mới 
        $product = Product::create($data);

        // 4. Xử lý danh sách biến thể màu sắc 
        $firstVariantImage = null;
        if ($request->has('variants') && is_array($request->input('variants'))) {
            foreach ($request->input('variants') as $index => $variantData) {
                if (!empty($variantData['color_name'])) {
                    $variantImage = null;
                   
                    if ($request->hasFile("variants.{$index}.image")) {
                        $vPath = $request->file("variants.{$index}.image")->store('variants', 'public');
                        $variantImage = '/storage/' . $vPath;
                        if (!$firstVariantImage) {
                            $firstVariantImage = $variantImage;
                        }
                    }

                    // Thêm bản ghi biến thể màu sắc vào bảng product_variants
                    $product->variants()->create([
                        'color_code' => $variantData['color_code'] ?? '#787878',
                        'color_name' => $variantData['color_name'],
                        'sku' => $variantData['sku'] ?? null,
                        'stock' => intval($variantData['stock'] ?? 0),
                        'image' => $variantImage,
                    ]);
                }
            }
        }

        if (!$product->thumbnail && $firstVariantImage) {
            $product->update(['thumbnail' => $firstVariantImage]);
        }

        return redirect()->route('admin.products.index')
                         ->with('success', 'Thêm mới sản phẩm gạch thành công!');
    }

    public function show(Product $product)
    {
        return redirect()->route('products.show', $product);
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load(['category', 'variants']);
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // 1. Kiểm tra tính hợp lệ của dữ liệu
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => ['required', 'string', 'max:100', Rule::unique('products')->ignore($product->id)],
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'size' => 'nullable|string|max:100',
            'area_per_box' => 'nullable|numeric|min:0',
            'pieces_per_box' => 'nullable|integer|min:0',
            'surface' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'color_pattern' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'anti_slip' => 'nullable|string|max:50',
            'weight_per_box' => 'nullable|numeric|min:0',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = $validated;
        $data['unit'] = $request->input('unit', 'Thùng (Hộp)');
        $data['is_featured'] = $request->has('is_featured') ? true : false;
        $data['status'] = $request->has('status') ? true : false;

        // 2. Xử lý thay đổi ảnh đại diện chính 
        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail && str_starts_with($product->thumbnail, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $product->thumbnail));
            }
            $path = $request->file('thumbnail')->store('products', 'public');
            $data['thumbnail'] = '/storage/' . $path;
        }

        $product->update($data);

        // 3. Đồng bộ hóa danh sách biến thể màu sắc (Sync Variants)
        if ($request->has('variants') && is_array($request->input('variants'))) {
            $submittedVariantIds = [];
            foreach ($request->input('variants') as $index => $variantData) {
                if (!empty($variantData['color_name'])) {
                    $variantId = $variantData['id'] ?? null;
                    
                    $vData = [
                        'color_code' => $variantData['color_code'] ?? '#787878',
                        'color_name' => $variantData['color_name'],
                        'sku' => $variantData['sku'] ?? null,
                        'stock' => intval($variantData['stock'] ?? 0),
                    ];

                    // Nếu có upload ảnh mới cho màu này
                    if ($request->hasFile("variants.{$index}.image")) {
                        $vPath = $request->file("variants.{$index}.image")->store('variants', 'public');
                        $vData['image'] = '/storage/' . $vPath;
                    }

                    if ($variantId) {
                        $variant = ProductVariant::find($variantId);
                        if ($variant && $variant->product_id === $product->id) {
                            $variant->update($vData);
                            $submittedVariantIds[] = $variant->id;
                        }
                    } else {
                        $newVariant = $product->variants()->create($vData);
                        $submittedVariantIds[] = $newVariant->id;
                    }
                }
            }

            $product->variants()->whereNotIn('id', $submittedVariantIds)->delete();
        } else {
            $product->variants()->delete();
        }

        return redirect()->route('admin.products.index')
                         ->with('success', 'Cập nhật sản phẩm gạch thành công!');
    }

    public function destroy(Product $product)
    {
        if ($product->thumbnail && str_starts_with($product->thumbnail, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $product->thumbnail));
        }

        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Đã xóa sản phẩm gạch thành công!');
    }
}
