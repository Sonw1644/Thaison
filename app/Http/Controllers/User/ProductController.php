<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        $product->load(['category', 'variants']);
        return view('products.show', compact('product'));
    }

    public function show_normal(Product $product)
    {
        return $this->show($product);
    }
}
