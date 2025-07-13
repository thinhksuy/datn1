@extends('layouts.layout')

@section('content')
    <h1>Danh sách Vai Trò</h1>

    <a href="{{ route('admin.roles.create') }}">Thêm vai trò mới</a>

    @if (session('success'))
        <div style="color: green">{{ session('success') }}</div>
    @endif

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên vai trò</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role->Role_ID }}</td>
                    <td>{{ $role->Name }}</td>
                    <td>{{ $role->Description }}</td>
                    <td>
                        <a href="{{ route('admin.roles.edit', $role->Role_ID) }}">Sửa</a>
                        <form action="{{ route('admin.roles.destroy', $role->Role_ID) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Xóa vai trò này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $roles->links() }}
@endsection
