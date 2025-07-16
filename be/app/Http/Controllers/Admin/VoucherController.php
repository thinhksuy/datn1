<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class VoucherController extends Controller
{
    public function index()
    {
       $vouchers = Voucher::paginate(10);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
{
    return view('admin.vouchers.create');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'code' => 'required|unique:vouchers,Code',
        'discount_type' => 'required|in:percentage,fixed',
        'discount_value' => 'required|numeric',
        'max_uses' => 'nullable|integer',
        'expires' => 'nullable|date',
        'applies_to' => 'nullable|string',
    ]);

    Voucher::create([
        'Code' => $validated['code'],
        'Discount_type' => $validated['discount_type'],
        'Discount_value' => $validated['discount_value'],
        'Max_uses' => $validated['max_uses'],
        'Expires' => $validated['expires'],
        'applies_to' => $validated['applies_to'],
    ]);

    return redirect()->route('admin.vouchers.index')->with('success', 'Tạo voucher thành công.');
}


    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, $id)
{
    // ✅ Bước 1: Validate dữ liệu đầu vào
    $validated = $request->validate([
        'code' => 'required|string|max:255|unique:vouchers,Code,' . $id . ',Vouchers_ID',
        'discount_type' => 'required|in:percentage,fixed',
        'discount_value' => 'required|numeric|min:0',
        'max_uses' => 'nullable|integer|min:0',
        'expires' => 'nullable|date',
        'applies_to' => 'nullable|string|max:255',
    ]);

    // ✅ Bước 2: Tìm voucher theo ID
    $voucher = Voucher::findOrFail($id);

    // ✅ Bước 3: Cập nhật thông tin từ dữ liệu đã validate
    $voucher->update([
    'Code' => $validated['code'],
    'Discount_type' => $validated['discount_type'],
    'Discount_value' => $validated['discount_value'],
    'Max_uses' => $validated['max_uses'],
    'Expires' => $validated['expires'],
    'applies_to' => $validated['applies_to'],
]);


    // ✅ Bước 4: Chuyển hướng và hiển thị thông báo
    return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật thành công');
}



    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();
        return redirect()->route('admin.vouchers.index')->with('success', 'Xoá thành công.');
    }
}
