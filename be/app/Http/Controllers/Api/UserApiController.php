<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserApiController extends Controller
{
    // GET /api/users
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    // GET /api/users/{id}
    public function show($id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user, 200);
    }

    // POST /api/users
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Role_ID'       => 'required|exists:roles,Role_ID',
            'Name'          => 'required|string|max:255',
            'Email'         => 'required|email|unique:user,Email',
            'Password'      => 'required|string|min:6',
            'Phone'         => 'nullable|string|max:20',
            'Gender'        => 'nullable|in:male,female,0,1',
            'Date_of_birth' => 'nullable|date',
            'Avatar'        => 'nullable|string',
            'Status'        => 'nullable|boolean',
            'Address'       => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'Role_ID'       => $request->Role_ID,
            'Name'          => $request->Name,
            'Email'         => $request->Email,
            'Password'      => Hash::make($request->Password),
            'Phone'         => $request->Phone,
            'Gender'        => $request->Gender,
            'Date_of_birth' => $request->Date_of_birth,
            'Avatar'        => $request->Avatar,
            'Status'        => $request->Status ?? 1,
            'Address'       => $request->Address,
        ]);

        return response()->json($user, 201);
    }

    // PUT /api/users/{id}
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'Role_ID'       => 'sometimes|required|exists:roles,Role_ID',
            'Name'          => 'sometimes|required|string|max:255',
            'Email'         => "sometimes|required|email|unique:user,Email,{$id},ID",
            'Password'      => 'sometimes|required|string|min:6',
            'Phone'         => 'nullable|string|max:20',
            'Gender'        => 'nullable|in:male,female,0,1',
            'Date_of_birth' => 'nullable|date',
            'Avatar'        => 'nullable|string',
            'Status'        => 'nullable|boolean',
            'Address'       => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Duyệt tất cả các field đã validate và gán
        foreach ($request->only([
            'Role_ID','Name','Email','Password',
            'Phone','Gender','Date_of_birth','Avatar',
            'Status','Address'
        ]) as $key => $value) {
            if ($key === 'Password') {
                $user->$key = Hash::make($value);
            } else {
                $user->$key = $value;
            }
        }
        $user->save();

        return response()->json($user, 200);
    }

    // DELETE /api/users/{id}
    public function destroy($id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted'], 200);
    }
}
