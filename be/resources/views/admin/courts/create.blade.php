@extends('layouts.layout')

@section('title', 'Thêm sân cầu lông')

@section('content')
<style>
    .form-container {
        max-width: 700px;
        margin: 30px auto;
        padding: 24px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .form-container h2 {
        margin-bottom: 20px;
        font-size: 22px;
        color: #2d3748;
    }

    .form-group {
        margin-bottom: 18px;
    }

    label {
        display: block;
        font-weight: 500;
        margin-bottom: 6px;
    }

    input[type="text"],
    input[type="number"],
    select,
    textarea {
        width: 100%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .form-error {
        color: #dc2626;
        font-size: 13px;
        margin-top: 4px;
    }

    textarea {
        resize: vertical;
    }

    .form-actions {
        text-align: right;
    }

    .form-actions button {
        background-color: #2563eb;
        color: #fff;
        padding: 10px 20px;
        border: none;
        font-weight: bold;
        border-radius: 4px;
        cursor: pointer;
    }

    .form-actions button:hover {
        background-color: #1e40af;
    }

    .form-note {
        font-size: 13px;
        color: #6b7280;
    }
</style>

<div class="form-container">
    <h2>Thêm Sân Cầu Lông</h2>

    @if ($errors->any())
        <div style="background:#fee2e2; color:#b91c1c; padding:12px 16px; border-radius:6px; margin-bottom:20px;">
            <strong>Có lỗi xảy ra:</strong>
            <ul style="margin-top:8px; list-style: disc; padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ⚠️ Đảm bảo route này chính xác là admin.courts.store --}}
    <form action="{{ route('admin.courts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="Name">Tên sân</label>
            <input type="text" id="Name" name="Name" value="{{ old('Name') }}" required>
            @error('Name') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="Location">Địa điểm</label>
            <input type="text" id="Location" name="Location" value="{{ old('Location') }}" required>
            @error('Location') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="Court_type">Loại sân</label>
            <input type="text" id="Court_type" name="Court_type" value="{{ old('Court_type') }}" required>
            @error('Court_type') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="Price_per_hour">Giá/giờ (VNĐ)</label>
            <input type="number" id="Price_per_hour" name="Price_per_hour" value="{{ old('Price_per_hour') }}" min="0" required>
            @error('Price_per_hour') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="Status">Trạng thái</label>
            <select id="Status" name="Status">
                <option value="1" {{ old('Status', 1) == 1 ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ old('Status') == "0" ? 'selected' : '' }}>Tạm ngưng</option>
            </select>
            @error('Status') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="Image">Ảnh sân <span class="form-note">(tùy chọn)</span></label>
            <input type="file" id="Image" name="Image" accept="image/*">
            @error('Image') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="Description">Mô tả</label>
            <textarea id="Description" name="Description" rows="4">{{ old('Description') }}</textarea>
            @error('Description') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-actions">
            <button type="submit">Thêm sân</button>
        </div>
    </form>
</div>
@endsection
