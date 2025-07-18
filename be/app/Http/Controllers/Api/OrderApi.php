<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderApi extends Controller
{
    public function index()
    {
        return response()->json(Order::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:user,id',
            'order_code' => 'required|unique:orders,order_code',
            'shipping_address' => 'required|string',
            'note_user' => 'nullable|string',
            'payment_method' => 'required|string',
            'shiping_fee' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'status' => 'required|string',
            'status_method' => 'nullable|string',
            'vourchers_id' => 'nullable|exists:vourchers,id',
        ]);

        $order = Order::create($validated);
        return response()->json($order, 201);
    }

    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
        }
        return response()->json($order, 200);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
        }

        $validated = $request->validate([
            'shipping_address' => 'sometimes|required|string',
            'note_user' => 'nullable|string',
            'payment_method' => 'sometimes|required|string',
            'shiping_fee' => 'nullable|numeric',
            'total_amount' => 'sometimes|required|numeric',
            'status' => 'sometimes|required|string',
            'status_method' => 'nullable|string',
            'vourchers_id' => 'nullable|exists:vourchers,id',
        ]);

        $order->update($validated);
        return response()->json($order, 200);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
        }

        $order->delete();
        return response()->json(['message' => 'Xóa đơn hàng thành công'], 200);
    }
}
