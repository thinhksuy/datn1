@extends('layouts.layout')

@section('content')
<h1>Thêm bài viết mới</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="User_ID">Người đăng</label>
        <select name="User_ID" id="User_ID" class="form-control" required>
            @foreach($users as $user)
                <option value="{{ $user->ID }}">{{ $user->Name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="Category_ID">Danh mục</label>
        <select name="Category_ID" id="Category_ID" class="form-control" required>
            <option value="" disabled selected>Chọn danh mục</option>
            @foreach($categories as $category)
                <option value="{{ $category->Post_Categories_ID }}">{{ $category->Title }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="Title">Tiêu đề</label>
        <input type="text" name="Title" id="Title" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="Thumbnail">Ảnh đại diện</label>
        <input type="file" name="Thumbnail" id="Thumbnail" class="form-control">
    </div>

    <div class="form-group">
        <label for="Content">Nội dung</label>
        <textarea name="Content" id="Content" class="form-control" rows="5" required></textarea>
    </div>

    <div class="form-group">
        <label for="Excerpt">Trích đoạn</label>
        <textarea name="Excerpt" id="Excerpt" class="form-control" rows="3"></textarea>
    </div>

    <div class="form-group">
        <label for="Status">Trạng thái</label>
        <input type="checkbox" name="Status" id="Status" value="1">
    </div>

    <!-- Removed View input field to prevent admin editing view count -->

    <button type="submit" class="btn btn-primary">Lưu</button>
</form>
@endsection
