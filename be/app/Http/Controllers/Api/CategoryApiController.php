<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category; // ✅ BẠT BUỘC PHẢI CÓ DÒNG NÀY

class CategoryApiController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }
}
