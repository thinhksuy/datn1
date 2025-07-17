@extends('layouts.layout')

@section('content')
<style>

</style>
<main>
    <div class="head-title">
        <div class="left">
            <h1>Chi Tiết Bài Viết</h1>
            <ul class="breadcrumb">
                <li><a href="#">Bài viết</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Chi tiết</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.posts.index') }}" class="btn-download">
            <span class="text">Quay lại</span>
        </a>
    </div>

    <div class="card post-detail" style="padding: 24px; border-radius: 12px; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto;">
        @if($post->Thumbnail)
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="{{ $post->Thumbnail }}" alt="Thumbnail" style="max-width: 100%; height: auto; border-radius: 12px;">
            </div>
        @endif

        <h2 style="margin-bottom: 10px;">{{ $post->Title }}</h2>

        <p><strong>Người đăng:</strong> {{ $post->user->Name ?? 'N/A' }}</p>
        <p><strong>Danh mục:</strong> {{ $post->category->Title ?? 'N/A' }}</p>
        <p><strong>Trạng thái:</strong> <span style="color: {{ $post->Status ? 'green' : 'red' }}">{{ $post->Status ? 'Hiển thị' : 'Ẩn' }}</span></p>
        <p><strong>Lượt xem:</strong> {{ $post->View }}</p>

        @if($post->Excerpt)
            <div style="margin-top: 16px;">
                <strong>Trích đoạn:</strong>
                <div style="background-color: #f9f9f9; padding: 12px; border-radius: 8px; margin-top: 6px;">
                    {{ $post->Excerpt }}
                </div>
            </div>
        @endif

        <div style="margin-top: 24px;">
            <strong>Nội dung:</strong>
            <div style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-top: 6px;">
                {!! nl2br(e($post->Content)) !!}
            </div>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 12px;">
            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
            <a href="{{ route('admin.posts.edit', $post->Post_ID) }}" class="btn btn-primary">Chỉnh sửa</a>
        </div>
    </div>
</main>
@endsection
