@extends('layouts.layout')

@section('content')
<h1>Chi tiết bài viết</h1>

<table class="clean-table">
    <tbody>
        <tr>
            <th>Tiêu đề</th>
            <td>{{ $post->Title }}</td>
        </tr>
        <tr>
            <th>Người đăng</th>
            <td>{{ $post->user->Name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Danh mục</th>
            <td>{{ $post->category->Title ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Trạng thái</th>
            <td>{{ $post->Status ? 'Hiển thị' : 'Ẩn' }}</td>
        </tr>
        <tr>
            <th>Lượt xem</th>
            <td>{{ $post->View }}</td>
        </tr>
        <tr>
            <th>Trích đoạn</th>
            <td>{{ $post->Excerpt }}</td>
        </tr>
        <tr>
            <th>Nội dung</th>
            <td>{!! nl2br(e($post->Content)) !!}</td>
        </tr>
        @if($post->Thumbnail)
        <tr>
            <th>Thumbnail</th>
            <td><img src="{{ $post->Thumbnail }}" alt="Thumbnail" style="max-width: 200px; border-radius: 8px;"></td>
        </tr>
        @endif
    </tbody>
</table>

<a href="{{ route('admin.posts.index') }}" class="btn btn-back">Quay lại danh sách</a>
<a href="{{ route('admin.posts.edit', $post->Post_ID) }}" class="btn btn-edit">Chỉnh sửa</a>
@endsection
