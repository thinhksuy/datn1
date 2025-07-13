<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherApiController extends Controller
{
    public function index()
    {
        return response()->json(Voucher::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Code' => 'required|unique:vouchers,Code',
            'Discount_type' => 'required|in:percentage,fixed',
            'Discount_value' => 'required|numeric',
            'Max_uses' => 'nullable|integer',
            'Expires' => 'nullable|date',
            'applies_to' => 'nullable|string',
            'Paid_at' => 'nullable|date',
        ]);

        $voucher = Voucher::create($validated);
        return response()->json($voucher, 201);
    }

    public function show($id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($voucher, 200);
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'Code' => 'sometimes|required|unique:vouchers,Code,' . $id . ',Vouchers_ID',
            'Discount_type' => 'sometimes|required|in:percentage,fixed',
            'Discount_value' => 'sometimes|required|numeric',
            'Max_uses' => 'nullable|integer',
            'Expires' => 'nullable|date',
            'applies_to' => 'nullable|string',
            'Paid_at' => 'nullable|date',
        ]);

        $voucher->update($validated);
        return response()->json($voucher, 200);
    }

    public function destroy($id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $voucher->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }
}
