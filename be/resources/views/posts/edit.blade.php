@extends('layouts.layout')

@section('content')
<h1>Chỉnh sửa bài viết</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.posts.update', $post->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="User_ID">Người đăng</label>
        <select name="User_ID" id="User_ID" class="form-control" required>
            @foreach($users as $user)
                <option value="{{ $user->ID }}" {{ $user->ID == $post->User_ID ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="Category_ID">Danh mục</label>
        <select name="Category_ID" id="Category_ID" class="form-control" required>
            @foreach($categories as $category)
                <option value="{{ $category->Post_Categories_ID }}" {{ $category->Post_Categories_ID == $post->Category_ID ? 'selected' : '' }}>{{ $category->Name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="Title">Tiêu đề</label>
        <input type="text" name="Title" id="Title" class="form-control" value="{{ $post->Title }}" required>
    </div>

    <div class="form-group">
        <label for="Thumbnail">Ảnh đại diện</label>
        <input type="text" name="Thumbnail" id="Thumbnail" class="form-control" value="{{ $post->Thumbnail }}">
    </div>

    <div class="form-group">
        <label for="Content">Nội dung</label>
        <textarea name="Content" id="Content" class="form-control" rows="5" required>{{ $post->Content }}</textarea>
    </div>

    <div class="form-group">
        <label for="Excerpt">Trích đoạn</label>
        <textarea name="Excerpt" id="Excerpt" class="form-control" rows="3">{{ $post->Excerpt }}</textarea>
    </div>

    <div class="form-group">
        <label for="Status">Trạng thái</label>
        <input type="checkbox" name="Status" id="Status" value="1" {{ $post->Status ? 'checked' : '' }}>
    </div>

    <div class="form-group">
        <label for="View">Lượt xem</label>
        <input type="number" name="View" id="View" class="form-control" value="{{ $post->View }}">
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật</button>
</form>
@endsection
