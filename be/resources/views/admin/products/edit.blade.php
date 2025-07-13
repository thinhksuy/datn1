@extends('layouts.layout')

@section('content')

<!-- =========================
     Tiêu đề trang
============================ -->
<div class="head-title">
    <div class="left">
        <h1>Sửa sản phẩm</h1>
        <ul class="breadcrumb">
            <li><a href="#">Sản phẩm</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Sửa sản phẩm</a></li>
        </ul>
    </div>
</div>

<!-- =========================
     Form cập nhật sản phẩm
============================ -->
<div class="form-edit">
    <h2>Cập nhật Sản phẩm</h2>

    <form action="{{ route('admin.products.update', $product->Product_ID) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Thông tin cơ bản -->
        <div class="form-group">
            <label for="Name">Tên sản phẩm</label>
            <input type="text" id="Name" name="Name" value="{{ old('Name', $product->Name) }}" required>
        </div>

        <div class="form-group">
            <label for="SKU">Mã SKU</label>
            <input type="text" id="SKU" name="SKU" value="{{ old('SKU', $product->SKU) }}">
        </div>

        <div class="form-group">
            <label for="Price">Giá</label>
            <input type="number" id="Price" name="Price" value="{{ old('Price', $product->Price) }}" required>
        </div>

        <div class="form-group">
            <label for="Discount_price">Giá khuyến mãi</label>
            <input type="number" id="Discount_price" name="Discount_price" value="{{ old('Discount_price', $product->Discount_price) }}">
        </div>

        <div class="form-group">
            <label for="Quantity">Số lượng</label>
            <input type="number" id="Quantity" name="Quantity" value="{{ old('Quantity', $product->Quantity) }}" required>
        </div>

        <div class="form-group">
            <label for="Brand">Thương hiệu</label>
            <input type="text" id="Brand" name="Brand" value="{{ old('Brand', $product->Brand) }}">
        </div>

        <div class="form-group">
            <label for="Description">Mô tả</label>
            <textarea id="Description" name="Description" rows="4">{{ old('Description', $product->Description) }}</textarea>
        </div>

        <!-- =========================
             Ảnh hiện tại của sản phẩm
        ============================= -->
        <div class="form-group">
            <label>Ảnh hiện tại:</label><br>

            <!-- Ảnh đại diện -->
            @if ($product->Image)
                <div style="margin-bottom: 10px;">
                    <strong>Ảnh đại diện:</strong><br>
                    <img src="{{ asset($product->Image) }}" width="100">
                </div>
            @else
                <em>Không có ảnh đại diện</em>
            @endif

            <!-- Ảnh phụ -->
            @if ($product->images && $product->images->count())
                <div style="margin-top: 10px;">
                    <strong>Ảnh phụ:</strong><br>
                    @foreach ($product->images as $image)
                        <img src="{{ asset($image->Image_path) }}" width="80" style="margin-right: 8px;">
                    @endforeach
                </div>
            @else
                <em>Không có ảnh phụ</em>
            @endif
        </div>

        <!-- =========================
             Tải lên ảnh mới
        ============================= -->
        <div class="form-group">
            <label for="Image">Cập nhật ảnh đại diện mới (nếu muốn)</label>
            <input type="file" id="Image" name="Image" accept="image/*">
        </div>

        <div class="form-group">
            <label for="Images">Thêm ảnh phụ mới (có thể chọn nhiều)</label>
            <input type="file" id="Images" name="Images[]" accept="image/*" multiple>
        </div>

        <!-- =========================
             Danh mục & Trạng thái
        ============================= -->
        <div class="form-group">
            <label for="Categories_ID">Danh mục</label>
            <select name="Categories_ID" id="Categories_ID" required>
                @foreach($categories as $category)
                    <option value="{{ $category->Categories_ID }}" {{ $category->Categories_ID == $product->Categories_ID ? 'selected' : '' }}>
                        {{ $category->Name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="Status">Trạng thái</label>
            <select id="Status" name="Status" required>
                <option value="1" {{ $product->Status ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ !$product->Status ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <!-- =========================
             Thuộc tính sản phẩm
        ============================= -->
        @if(isset($attributes) && count($attributes) > 0)
            <hr>
            <h3>Thuộc tính sản phẩm</h3>
            @foreach ($attributes as $attribute)
                <div class="form-group">
                    <label>{{ $attribute->Name }}</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                        @foreach ($attribute->values as $value)
                            <label>
                                <input type="checkbox"
                                       name="variant[Values_IDs][]"
                                       value="{{ $value->Values_ID }}"
                                       {{ in_array($value->Values_ID, $selectedValueIDs ?? []) ? 'checked' : '' }}>
                                {{ $value->Value }}
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

        <!-- =========================
             SKU của biến thể chính
        ============================= -->
        <div class="form-group">
            <label for="variant_SKU">Mã SKU cho biến thể</label>
            <input type="text" id="variant_SKU" name="variant[SKU]" value="{{ old('variant.SKU', $variantSKU ?? '') }}">
        </div>

        <!-- =========================
             Nút submit
        ============================= -->
        <div class="form-actions">
            <button type="submit">Cập nhật sản phẩm</button>
        </div>
    </form>
</div>

@endsection
