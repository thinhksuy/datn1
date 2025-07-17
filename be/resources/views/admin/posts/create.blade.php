@extends('layouts.layout')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Thêm Bài Viết</h1>
            <ul class="breadcrumb">
                <li><a href="#">Bài viết</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Thêm bài viết</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.posts.index') }}" class="btn-download">
            <span class="text">Quay lại</span>
        </a>
    </div>

    {{-- Hiển thị lỗi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-add">
        <h2>Thêm Bài Viết Mới</h2>
        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="User_ID">Người đăng</label>
                <select name="User_ID" id="User_ID" required>
                    @foreach($users as $user)
                        <option value="{{ $user->ID }}">{{ $user->Name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="Category_ID">Danh mục</label>
                <select name="Category_ID" id="Category_ID" required>
                    <option value="" disabled selected>Chọn danh mục</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->Post_Categories_ID }}">{{ $category->Title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="Title">Tiêu đề</label>
                <input type="text" name="Title" id="Title" required>
            </div>

            <div class="form-group">
                <label for="Thumbnail">Ảnh đại diện</label>
                <input type="file" name="Thumbnail" id="Thumbnail">
            </div>

            <div class="form-group">
                <label for="Content">Nội dung</label>
                <textarea name="Content" id="Content" rows="5" required></textarea>
            </div>

            <div class="form-group">
                <label for="Excerpt">Trích đoạn</label>
                <textarea name="Excerpt" id="Excerpt" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="Status">Trạng thái</label>
                <select name="Status">
                    <option value="1">Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>

            </div>

            <div class="form-actions">
                <button type="submit">Lưu</button>
            </div>
        </form>
    </div>
</main>
@endsection
