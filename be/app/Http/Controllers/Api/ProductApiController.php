<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductApiController extends Controller
{
    /**
     * Lấy danh sách sản phẩm có phân trang (12 sản phẩm mỗi trang)
     */
    public function index()
    {
        $products = Product::with('category')->paginate(12);
        return response()->json($products, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Lấy chi tiết sản phẩm theo ID
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return response()->json($product, 200, [], JSON_PRETTY_PRINT);
    }
}
