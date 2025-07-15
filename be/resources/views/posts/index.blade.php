@extends('layouts.layout')

@section('content')
<h1>Danh sách bài viết</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('admin.posts.create') }}" class="btn btn-primary">Thêm bài viết mới</a>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Người đăng</th>
            <th>Danh mục</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($posts as $post)
        <tr>
            <td>{{ $post->id }}</td>
            <td>{{ $post->Title }}</td>
            <td>{{ $post->user->name ?? 'N/A' }}</td>
            <td>{{ $post->category->Name ?? 'N/A' }}</td>
            <td>{{ $post->Status ? 'Hiển thị' : 'Ẩn' }}</td>
            <td>
                <a href="{{ route('admin.posts.show', $post->id) }}" class="btn btn-info">Xem</a>
                <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-warning">Sửa</a>
                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa bài viết này?')" class="btn btn-danger">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
