@extends('layouts.layout')

@section('title', 'Chỉnh sửa danh mục')

@section('content')
<h1>Chỉnh sửa danh mục</h1>

<form action="{{ route('admin.categories.update', $category->Categories_ID) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div>
        <label for="Name">Tên danh mục</label>
        <input type="text" id="Name" name="Name" value="{{ old('Name', $category->Name) }}" required>
    </div>

    <div>
        <label for="Image">Ảnh danh mục</label>
        <input type="file" id="Image" name="Image" accept="image/*">
        @if($category->Image)
            <img src="{{ asset($category->Image) }}" alt="Ảnh hiện tại" width="100">
        @endif
    </div>

    <div>
        <label for="Description">Mô tả</label>
        <textarea id="Description" name="Description">{{ old('Description', $category->Description) }}</textarea>
    </div>

    <button type="submit">Cập nhật</button>
</form>
@endsection
