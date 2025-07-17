@extends('layouts.layout')

@section('content')

<!-- =========================
     Tiêu đề trang
============================ -->
<div class="head-title">
    <div class="left">
        <h1>Thêm sản phẩm</h1>
        <ul class="breadcrumb">
            <li><a href="#">Sản phẩm</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Thêm sản phẩm</a></li>
        </ul>
    </div>
     <a href="{{ route('admin.products.index') }}" class="btn-download">
		<span class="text">Quay lại</span>
	</a>
</div>

<!-- =========================
     Form thêm sản phẩm mới
============================ -->
<div class="form-add">
    <h2>Thêm Sản Phẩm Mới</h2>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Thông tin cơ bản -->
        <div class="form-group">
            <label for="Name">Tên sản phẩm</label>
            <input type="text" id="Name" name="Name" required>
        </div>

        <div class="form-group">
            <label for="SKU">Mã SKU</label>
            <input type="text" id="SKU" name="SKU">
        </div>

        <div class="form-group">
            <label for="Price">Giá</label>
            <input type="number" id="Price" name="Price" required>
        </div>

        <div class="form-group">
            <label for="Discount_price">Giá khuyến mãi</label>
            <input type="number" id="Discount_price" name="Discount_price">
        </div>

        <div class="form-group">
            <label for="Quantity">Số lượng</label>
            <input type="number" id="Quantity" name="Quantity" required>
        </div>

        <div class="form-group">
            <label for="Brand">Thương hiệu</label>
            <input type="text" id="Brand" name="Brand">
        </div>

        <div class="form-group">
            <label for="Description">Mô tả</label>
            <textarea id="Description" name="Description" rows="4"></textarea>
        </div>

        <!-- Ảnh sản phẩm -->
        <div class="form-group">
            <label for="Image">Ảnh đại diện (1 ảnh)</label>
            <input type="file" id="Image" name="Image" accept="image/*">
        </div>

        <div class="form-group">
            <label for="Images">Ảnh phụ (nhiều ảnh)</label>
            <input type="file" id="Images" name="Images[]" multiple accept="image/*">
        </div>

        <!-- Danh mục & Trạng thái -->
        <div class="form-group">
            <label for="Categories_ID">Danh mục</label>
            <select name="Categories_ID" id="Categories_ID" required>
                @foreach($categories as $category)
                    <option value="{{ $category->Categories_ID }}">{{ $category->Name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="Status">Trạng thái</label>
            <select id="Status" name="Status" required>
                <option value="1">Hiển thị</option>
                <option value="0">Ẩn</option>
            </select>
        </div>

        <!-- Thuộc tính sản phẩm -->
        @if(isset($attributes) && count($attributes) > 0)
            <hr>
            <h3>Thuộc tính sản phẩm</h3>
            @foreach ($attributes as $attribute)
                <div class="form-group">
                    <label><strong>{{ $attribute->Name }}</strong></label>
                    <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 5px;">
                        @foreach ($attribute->values as $value)
                            <label style="margin-right: 10px;">
                                <input type="checkbox"
                                       name="variant[Values_IDs][]"
                                       value="{{ $value->Values_ID }}">
                                {{ $value->Value }}
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

        <!-- SKU cho biến thể chính -->
        <div class="form-group">
            <label for="variant_SKU">Mã SKU cho biến thể</label>
            <input type="text" id="variant_SKU" name="variant[SKU]" required>
        </div>

        <!-- Submit -->
        <div class="form-actions">
            <button type="submit">Thêm sản phẩm</button>
        </div>
    </form>
</div>

@endsection
