<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderDetailApi extends Controller
{
    public function index()
    {
        return response()->json(OrderDetail::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,order_id',
            'Product_ID' => 'required|exists:products,Product_ID',
            'product_name' => 'required|string|max:255',
            'SKU' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
        ]);

        $detail = OrderDetail::create($validated);
        return response()->json($detail, 201);
    }

    public function show($id)
    {
        $detail = OrderDetail::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Không tìm thấy chi tiết đơn hàng'], 404);
        }

        return response()->json($detail, 200);
    }

    public function update(Request $request, $id)
    {
        $detail = OrderDetail::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Không tìm thấy chi tiết đơn hàng'], 404);
        }

        $validated = $request->validate([
            'product_name' => 'sometimes|required|string|max:255',
            'SKU' => 'sometimes|required|string|max:100',
            'price' => 'sometimes|required|numeric|min:0',
            'quantity' => 'sometimes|required|integer|min:1',
            'total' => 'sometimes|required|numeric|min:0',
        ]);

        $detail->update($validated);
        return response()->json($detail, 200);
    }

    public function destroy($id)
    {
        $detail = OrderDetail::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Không tìm thấy chi tiết đơn hàng'], 404);
        }

        $detail->delete();
        return response()->json(['message' => 'Xóa chi tiết đơn hàng thành công'], 200);
    }
}
