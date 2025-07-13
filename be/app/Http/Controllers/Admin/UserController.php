<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Hiển thị danh sách user
    public function index(Request $request)
{
    $query = \App\Models\User::with('role');

    if ($request->filled('keyword')) {
        $query->where(function ($q) use ($request) {
            $q->where('Name', 'like', '%' . $request->keyword . '%')
              ->orWhere('Email', 'like', '%' . $request->keyword . '%');
        });
    }

    if ($request->filled('role')) {
        $query->where('Role_ID', $request->role);
    }

    if ($request->filled('status')) {
        $query->where('Status', $request->status);
    }

    $users = $query->paginate(10)->appends($request->query());
    $roles = \App\Models\Role::all();

    return view('admin.users.index', compact('users', 'roles'));
}

    // Hiển thị form tạo user
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    // Lưu user mới vào DB
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name'          => 'required|string|max:255',
            'Email'         => 'required|email|unique:user,Email',
            'Password'      => 'required|string|min:6|confirmed',
            'Phone'         => 'nullable|string|max:20',
            'Gender'        => 'nullable|in:male,female,other',
            'Date_of_birth' => 'nullable|date',
            'Status'        => 'nullable|boolean',
            'Address'       => 'nullable|string|max:500',
            'Role_ID'       => 'required|exists:roles,Role_ID',
        ]);

        $user = new User();
        $user->fill($validated);
        $user->Password = bcrypt($validated['Password']);
        $user->Status = $request->Status ?? 0;
        $user->Created_at = now();
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Tạo user thành công!');
    }

    // ========================
    // Hiển thị form chỉnh sửa
    // ========================
    public function edit($id)
    {
        $user  = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // =====================
    // Cập nhật user đã có
    // =====================
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'Name'          => 'required|string|max:255',
            'Email'         => [
                'required',
                'email',
                Rule::unique('user', 'Email')->ignore($user->ID, 'ID'),
            ],
            'Password'      => 'nullable|string|min:6|confirmed',
            'Phone'         => 'nullable|string|max:20',
            'Gender'        => 'nullable|in:male,female,other',
            'Date_of_birth' => 'nullable|date',
            'Status'        => 'nullable|boolean',
            'Address'       => 'nullable|string|max:500',
            'Role_ID'       => 'required|exists:roles,Role_ID',
        ]);

        $data = $validated;
        unset($data['Password']);

        $user->fill($data);

        if ($request->filled('Password')) {
            $user->Password = bcrypt($request->Password);
        }

        $user->Status = $request->Status ?? 0;
        $user->Updated_at = now();
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật user thành công!');
    }

    // =====================
    // Xóa user khỏi hệ thống
    // =====================
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Xóa user thành công!');
    }
}
