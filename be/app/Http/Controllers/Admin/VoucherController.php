<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all();
        return view('vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('vouchers.create');
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

        Voucher::create($validated);
        return redirect()->route('vouchers.index')->with('success', 'Voucher created successfully.');
    }

    public function show($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('vouchers.show', compact('voucher'));
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $validated = $request->validate([
            'Code' => 'required|unique:vouchers,Code,' . $id . ',Vouchers_ID',
            'Discount_type' => 'required|in:percentage,fixed',
            'Discount_value' => 'required|numeric',
            'Max_uses' => 'nullable|integer',
            'Expires' => 'nullable|date',
            'applies_to' => 'nullable|string',
            'Paid_at' => 'nullable|date',
        ]);

        $voucher->update($validated);
        return redirect()->route('vouchers.index')->with('success', 'Voucher updated successfully.');
    }

    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();
        return redirect()->route('vouchers.index')->with('success', 'Voucher deleted successfully.');
    }
}
