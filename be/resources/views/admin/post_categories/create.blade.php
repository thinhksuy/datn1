@extends('layouts.layout')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Thêm Danh Mục Bài Viết</h1>
            <ul class="breadcrumb">
                <li><a href="#">Bài viết</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Thêm danh mục</a></li>
            </ul>
        </div>
    </div>

    <div class="form-add">
        <h2>Thêm Danh Mục Mới</h2>

        {{-- Hiển thị lỗi --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.post_categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Tên danh mục</label>
                <input type="text" id="title" name="Title" value="{{ old('Title') }}" required>
            </div>

            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="Content" rows="4">{{ old('Content') }}</textarea>
            </div>

            <div class="form-group">
                <label for="status">Trạng thái</label>
                <select id="status" name="Status" required>
                    <option value="1" {{ old('Status') == '1' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ old('Status') == '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit">Thêm Danh Mục</button>
            </div>
        </form>
    </div>
</main>

@endsection
