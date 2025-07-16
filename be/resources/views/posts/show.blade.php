@extends('layouts.layout')

@section('content')
<h1>Chi tiết bài viết</h1>

<div>
    <h2>{{ $post->Title }}</h2>
    <p><strong>Người đăng:</strong> {{ $post->user->name ?? 'N/A' }}</p>
    <p><strong>Danh mục:</strong> {{ $post->category->Name ?? 'N/A' }}</p>
    <p><strong>Trạng thái:</strong> {{ $post->Status ? 'Hiển thị' : 'Ẩn' }}</p>
    <p><strong>Lượt xem:</strong> {{ $post->View }}</p>
    <p><strong>Trích đoạn:</strong> {{ $post->Excerpt }}</p>
    <div>
        <strong>Nội dung:</strong>
        <div>{!! nl2br(e($post->Content)) !!}</div>
    </div>
    @if($post->Thumbnail)
    <div>
        <img src="{{ $post->Thumbnail }}" alt="Thumbnail" style="max-width: 300px;">
    </div>
    @endif
</div>

<a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
<a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-primary">Chỉnh sửa</a>
@endsection
