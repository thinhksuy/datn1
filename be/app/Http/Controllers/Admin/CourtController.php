<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Court;
use Illuminate\Support\Facades\Storage;

class CourtController extends Controller
{
    // Hiển thị danh sách sân
    public function index()
    {
        $courts = Court::latest()->paginate(10);
        return view('admin.courts.index', compact('courts'));
    }

    // Hiển thị form thêm sân mới
    public function create()
    {
        return view('admin.courts.create');
    }

    // Xử lý lưu sân mới
    public function store(Request $request)
    {
        $request->validate([
            'Name'            => 'required|string|max:255',
            'Location'        => 'required|string|max:255',
            'Court_type'      => 'required|string|max:50',
            'Price_per_hour'  => 'required|numeric|min:0',
            'Image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'Name', 'Location', 'Description', 'Court_type', 'Price_per_hour', 'Status'
        ]);

        if ($request->hasFile('Image')) {
            $data['Image'] = $request->file('Image')->store('courts', 'public');
        }

        $data['Created_at'] = now();
        $data['Updated_at'] = now();

        Court::create($data);

        return redirect()->route('admin.courts.index')->with('success', 'Thêm sân thành công!');
    }

    // Hiển thị form sửa sân
    public function edit($id)
    {
        $court = Court::findOrFail($id);
        return view('admin.courts.edit', compact('court'));
    }

    // Cập nhật sân
    public function update(Request $request, $id)
    {
        $court = Court::findOrFail($id);

        $request->validate([
            'Name'            => 'required|string|max:255',
            'Location'        => 'required|string|max:255',
            'Court_type'      => 'required|string|max:50',
            'Price_per_hour'  => 'required|numeric|min:0',
            'Image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'Name', 'Location', 'Description', 'Court_type', 'Price_per_hour', 'Status'
        ]);

        if ($request->hasFile('Image')) {
            if ($court->Image && Storage::disk('public')->exists($court->Image)) {
                Storage::disk('public')->delete($court->Image);
            }

            $data['Image'] = $request->file('Image')->store('courts', 'public');
        }

        $data['Updated_at'] = now();

        $court->update($data);

        return redirect()->route('admin.courts.index')->with('success', 'Cập nhật sân thành công!');
    }

    // Xóa sân
    public function destroy($id)
    {
        $court = Court::findOrFail($id);

        if ($court->Image && Storage::disk('public')->exists($court->Image)) {
            Storage::disk('public')->delete($court->Image);
        }

        $court->delete();

        return redirect()->route('admin.courts.index')->with('success', 'Xóa sân thành công!');
    }
}
