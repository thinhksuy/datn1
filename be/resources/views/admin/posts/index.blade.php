@extends('layouts.layout')

@section('content')
<h1>Danh sách bài viết</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('admin.posts.create') }}" class="btn btn-primary" style="margin-bottom: 15px;">Thêm bài viết mới</a>

<table class="clean-table" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px; color: #333;">
    <thead>
        <tr style="background-color: #f4f6f8; font-weight: 600; color: #222;">
            <th style="border: 1px solid #ddd; padding: 10px 15px; text-align: left;">ID</th>
            <th style="border: 1px solid #ddd; padding: 10px 15px; text-align: left;">Tiêu đề</th>
            <th style="border: 1px solid #ddd; padding: 10px 15px; text-align: left;">Người đăng</th>
            <th style="border: 1px solid #ddd; padding: 10px 15px; text-align: left;">Danh mục</th>
            <th style="border: 1px solid #ddd; padding: 10px 15px; text-align: left;">Trạng thái</th>
            <th style="border: 1px solid #ddd; padding: 10px 15px; text-align: left;">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($posts as $post)
        <tr style="background-color: {{ $loop->even ? '#f9fafb' : 'white' }}; cursor: pointer;">
            <td style="border: 1px solid #ddd; padding: 10px 15px;">{{ $post->Post_ID }}</td>
            <td style="border: 1px solid #ddd; padding: 10px 15px;">{{ $post->Title }}</td>
            <td style="border: 1px solid #ddd; padding: 10px 15px;">{{ $post->user->Name ?? 'N/A' }}</td>
            <td style="border: 1px solid #ddd; padding: 10px 15px;">{{ $post->category->Title ?? 'N/A' }}</td>
            <td style="border: 1px solid #ddd; padding: 10px 15px;">{{ $post->Status ? 'Hiển thị' : 'Ẩn' }}</td>
            <td style="border: 1px solid #ddd; padding: 10px 15px;">
                <a href="{{ route('admin.posts.show', $post->Post_ID) }}" class="btn btn-info" style="color: #1a73e8; text-decoration: none; margin-right: 5px;">Xem</a>
                <a href="{{ route('admin.posts.edit', $post->Post_ID) }}" class="btn btn-warning" style="color: #1a73e8; text-decoration: none; margin-right: 5px;">Sửa</a>
                <form action="{{ route('admin.posts.destroy', $post->Post_ID) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa bài viết này?')" class="btn btn-danger" style="background-color: #ff4d4f; border: none; color: white; padding: 5px 10px; cursor: pointer;">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
