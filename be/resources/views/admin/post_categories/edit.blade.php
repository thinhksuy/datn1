@extends('layouts.layout')

@section('content')
<main>
<div class="head-title">
    <div class="left">
        <h1>Sửa danh mục bài viết</h1>
        <ul class="breadcrumb">
            <li><a href="#">Danh mục bài viết</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Sửa danh mục bài viết</a></li>
        </ul>
    </div>
     <a href="{{ route('admin.post_categories.index') }}" class="btn-download">
		<span class="text">Quay lại</span>
	</a>
</div>

<div class="form-add">
    <h2>Cập nhật Danh Mục</h2>
    <form action="{{ route('admin.post_categories.update', $category->Post_Categories_ID) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Tên danh mục</label>
            <input type="text" id="title" name="Title" value="{{ old('Title', $category->Title) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea id="description" name="Content" rows="4" required>{{ old('Content', $category->Content) }}</textarea>
        </div>

        <div class="form-group">
            <label for="status">Trạng thái</label>
            <select id="status" name="Status" required>
                <option value="1" {{ old('Status', $category->Status) == '1' ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ old('Status', $category->Status) == '0' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit">Cập nhật Danh Mục</button>
        </div>
    </form>
</div>

</main>

@endsection
