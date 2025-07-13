@extends('layouts.layout')

@section('content')
<h2>Sửa Vai Trò</h2>
<form action="{{ route('admin.roles.update', $role->Role_ID) }}" method="POST">
    @csrf
    @method('PUT')
    <label>Tên vai trò:</label>
    <input type="text" name="Name" value="{{ $role->Name }}" required><br><br>

    <label>Mô tả:</label>
    <textarea name="Description">{{ $role->Description }}</textarea><br><br>

    <button type="submit">Cập nhật</button>
</form>
@endsection
