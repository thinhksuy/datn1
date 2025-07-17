@extends('layouts.layout')

@section('title', 'Thêm sân cầu lông')

@section('content')

<div class="head-title">
        <div class="left">
            <h1>Chỉnh sửa sân</h1>
            <ul class="breadcrumb">
                <li><a href="#">Quản lí sân</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Chỉnh sửa sân</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.courts.index') }}" class="btn-download">
            <span class="text">Quay lại</span>
	</a>
    </div>
<div class="form-add">
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
