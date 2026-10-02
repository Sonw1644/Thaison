<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;

class CartController extends Controller
{
   
    public function index()
    {
        $cart = session()->get('cart', []);

        // Lấy danh sách ID sản phẩm có trong giỏ hàng để nạp danh sách biến thể tương ứng
        $productIds = collect($cart)->pluck('product_id')->filter()->unique();
        $products = Product::with('variants')->whereIn('id', $productIds)->get()->keyBy('id');

        return view('cart.index', compact('cart', 'products'));
    }

    public function add(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        $quantity = (int) $request->input('quantity', 1);
        if ($quantity <= 0) {
            $quantity = 1;
        }

        // Lấy thông tin biến thể màu nếu người dùng đã chọn
        $variant = null;
        if ($request->filled('variant_id')) {
            $variant = ProductVariant::where('product_id', $product->id)
                                      ->where('id', $request->input('variant_id'))
                                      ->first();
        }

        // Nếu sản phẩm có biến thể nhưng chưa chọn, mặc định lấy biến thể đầu tiên
        if (!$variant && $product->variants()->count() > 0) {
            $variant = $product->variants()->first();
        }

        // Tạo key giỏ hàng duy nhất theo sản phẩm và màu sắc
        $cartKey = $variant ? ($product->id . '_' . $variant->id) : (string)$product->id;
        $itemName = $product->name . ($variant ? ' (' . $variant->color_name . ')' : '');
        $itemThumbnail = ($variant && !empty($variant->image)) ? $variant->image : $product->thumbnail;

        // Nếu món hàng này đã có trong giỏ, tăng thêm số lượng
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            // Thêm mới món hàng vào giỏ
            $cart[$cartKey] = [
                "product_id"   => $product->id,
                "variant_id"   => $variant ? $variant->id : null,
                "color_name"   => $variant ? $variant->color_name : null,
                "name"         => $itemName,
                "quantity"     => $quantity,
                "price"        => $product->price,
                "category"     => optional($product->category)->name ?? 'Chưa phân loại',
                "thumbnail"    => $itemThumbnail,
            ];
        }

        session()->put('cart', $cart);

        if ($request->input('action') === 'buy_now') {
            return redirect()->route('checkout.index');
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng thành công.');
    }

    
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được xoá khỏi giỏ hàng.');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $quantity = (int) $request->input('quantity', $cart[$id]['quantity'] ?? 1);
            if ($quantity <= 0) {
                $quantity = 1;
            }

            // Lấy ID sản phẩm gốc
            $productId = $cart[$id]['product_id'] ?? explode('_', $id)[0];
            $product = Product::with('variants')->find($productId);

            if (!$product) {
                return redirect()->route('cart.index')->with('error', 'Sản phẩm không tồn tại!');
            }

            // Kiểm tra nếu người dùng đổi màu sắc (biến thể)
            $newVariantId = $request->input('variant_id', $cart[$id]['variant_id'] ?? null);
            $variant = null;
            if ($newVariantId) {
                $variant = ProductVariant::where('product_id', $product->id)->where('id', $newVariantId)->first();
            }

            $newCartKey = $variant ? ($product->id . '_' . $variant->id) : (string)$product->id;
            $itemName = $product->name . ($variant ? ' (' . $variant->color_name . ')' : '');
            $itemThumbnail = ($variant && !empty($variant->image)) ? $variant->image : $product->thumbnail;

            // Nếu đổi màu tạo ra key mới khác với key cũ
            if ($newCartKey !== (string)$id) {
                unset($cart[$id]); // Xóa item cũ

                // Nếu giỏ đã có sẵn màu mới này rồi thì cộng dồn số lượng
                if (isset($cart[$newCartKey])) {
                    $cart[$newCartKey]['quantity'] += $quantity;
                } else {
                    $cart[$newCartKey] = [
                        "product_id" => $product->id,
                        "variant_id" => $variant ? $variant->id : null,
                        "color_name" => $variant ? $variant->color_name : null,
                        "name"       => $itemName,
                        "quantity"   => $quantity,
                        "price"      => $product->price,
                        "category"   => optional($product->category)->name ?? 'Chưa phân loại',
                        "thumbnail"  => $itemThumbnail,
                    ];
                }
            } else {
                // Cùng key, cập nhật lại số lượng, tên và hình ảnh
                $cart[$id]['quantity']   = $quantity;
                $cart[$id]['name']       = $itemName;
                $cart[$id]['thumbnail']  = $itemThumbnail;
                $cart[$id]['variant_id'] = $variant ? $variant->id : null;
                $cart[$id]['color_name'] = $variant ? $variant->color_name : null;
            }

            session()->put('cart', $cart);

            return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã được cập nhật thành công!');
        }

        return redirect()->route('cart.index')->with('error', 'Cập nhật thất bại!');
    }
}
