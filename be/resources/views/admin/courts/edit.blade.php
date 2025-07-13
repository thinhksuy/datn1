@extends('layouts.layout')

@section('title', 'Chỉnh sửa sân cầu lông')

@section('content')
<style>
    .container {
        max-width: 700px;
        margin: 40px auto;
        padding: 0 16px;
    }

    h1 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 24px;
        color: #2d3748;
    }

    form {
        background-color: #ffffff;
        padding: 24px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
    }

    input[type="text"],
    input[type="number"],
    textarea,
    select {
        width: 100%;
        padding: 10px;
        border: 1px solid #cbd5e0;
        border-radius: 4px;
        margin-bottom: 16px;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-check {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .form-check input {
        margin-right: 8px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn {
        padding: 10px 16px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        font-weight: bold;
    }

    .btn-save {
        background-color: #2563eb;
        color: white;
    }

    .btn-save:hover {
        background-color: #1d4ed8;
    }

    .btn-cancel {
        background-color: #e5e7eb;
        color: #374151;
        text-decoration: none;
        display: inline-block;
        padding: 10px 16px;
        border-radius: 4px;
    }

    .btn-cancel:hover {
        background-color: #d1d5db;
    }

    .image-preview {
        width: 150px;
        height: 100px;
        object-fit: cover;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-top: 8px;
    }
</style>

<div class="container">
    <h1>Chỉnh sửa sân: {{ $court->Name }}</h1>

    <form action="{{ route('admin.courts.update', $court->Courts_ID) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="Name">Tên sân</label>
            <input type="text" name="Name" id="Name" value="{{ old('Name', $court->Name) }}" required>
        </div>

        <div class="form-group">
            <label for="Location">Vị trí</label>
            <input type="text" name="Location" id="Location" value="{{ old('Location', $court->Location) }}" required>
        </div>

        <div class="form-group">
            <label for="Court_type">Loại sân</label>
            <input type="text" name="Court_type" id="Court_type" value="{{ old('Court_type', $court->Court_type) }}" required>
        </div>

        <div class="form-group">
            <label for="Price_per_hour">Giá/giờ (VNĐ)</label>
            <input type="number" name="Price_per_hour" id="Price_per_hour" step="1000" value="{{ old('Price_per_hour', $court->Price_per_hour) }}" required>
        </div>

        <div class="form-group">
            <label for="Description">Mô tả</label>
            <textarea name="Description" id="Description" rows="4">{{ old('Description', $court->Description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="Image">Hình ảnh mới (tùy chọn)</label>
            <input type="file" name="Image" id="Image" accept="image/*">
            @if($court->Image)
                <img src="{{ asset('storage/' . $court->Image) }}" alt="Court Image" class="image-preview">
            @endif
        </div>

        <div class="form-check">
            <input type="checkbox" name="Status" id="Status" value="1" {{ $court->Status ? 'checked' : '' }}>
            <label for="Status">Hiển thị sân (trạng thái hoạt động)</label>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.courts.index') }}" class="btn btn-cancel">Hủy</a>
            <button type="submit" class="btn btn-save">Lưu thay đổi</button>
        </div>
    </form>
</div>
@endsection
