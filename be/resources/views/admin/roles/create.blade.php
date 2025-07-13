@extends('layouts.layout')

@section('content')
<h2>Thêm Vai Trò</h2>
<form action="{{ route('admin.roles.store') }}" method="POST">
    @csrf
    <label>Tên vai trò:</label>
    <input type="text" name="Name" required><br><br>

    <label>Mô tả:</label>
    <textarea name="Description"></textarea><br><br>

    <button type="submit">Lưu</button>
</form>
@endsection
