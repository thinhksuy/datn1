<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Court;

class CourtApi extends Controller
{
    // GET /api/courts
    public function index()
    {
        return response()->json(Court::all(), 200);
    }

    // POST /api/courts
    public function store(Request $request)
{
    $data = $request->validate([
        'Name'           => 'required|string|max:255',
        'Location'       => 'required|string|max:255',
        'Description'    => 'nullable|string',
        'Image'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'Court_type'     => 'required|string|max:100',
        'Price_per_hour' => 'required|numeric|min:0',
        'Status'         => 'nullable|boolean',
    ]);

    // 👇 Xử lý upload ảnh
    if ($request->hasFile('Image')) {
        $file = $request->file('Image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/courts'), $filename);
        $data['Image'] = $filename;
    }

    $data['Created_at'] = now();

    $court = Court::create($data);

    return response()->json([
        'message' => 'Tạo sân thành công',
        'data'    => $court
    ], 201);
}


    // GET /api/courts/{id}
    public function show($id)
    {
        $court = Court::findOrFail($id);
        return response()->json($court);
    }

    // PUT/PATCH /api/courts/{id}
    public function update(Request $request, $id)
{
    $court = Court::findOrFail($id);

    $data = $request->validate([
        'Name'           => 'sometimes|required|string|max:255',
        'Location'       => 'sometimes|required|string|max:255',
        'Description'    => 'nullable|string',
        'Image'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'Court_type'     => 'sometimes|required|string|max:100',
        'Price_per_hour' => 'sometimes|required|numeric|min:0',
        'Status'         => 'nullable|boolean',
    ]);

    // 👇 Xử lý upload ảnh mới (nếu có)
    if ($request->hasFile('Image')) {
        $file = $request->file('Image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/courts'), $filename);
        $data['Image'] = $filename;
    }

    $data['Updated_at'] = now();

    $court->update($data);

    return response()->json([
        'message' => 'Cập nhật thành công',
        'data'    => $court
    ]);
}


    // DELETE /api/courts/{id}
    public function destroy($id)
    {
        $court = Court::findOrFail($id);
        $court->delete();

        return response()->json(['message' => 'Xóa sân thành công'], 200);
    }
}
