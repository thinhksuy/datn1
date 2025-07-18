@extends('layouts.layout')

@section('content')

<style>
    /* Phần form lọc tổng thể */
.filter-form {
    background-color: #fdfdfd;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    font-size: 14px;
}

/* Container sử dụng flex (nên để class riêng thay vì inline style) */
.filter-form > div {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: flex-end;
}

/* Nhóm label + input/select */
.filter-form div > label {
    display: block;
    font-weight: 500;
    margin-bottom: 5px;
    color: #333;
}

.filter-form input[type="text"],
.filter-form input[type="number"],
.filter-form select {
    width: 250px;
    padding: 8px 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    background-color: #fff;
    transition: border-color 0.3s;
}

.filter-form input:focus,
.filter-form select:focus {
    border-color: #3b82f6;
    outline: none;
}

</style>
<!-- =========================
     Tiêu đề và breadcrumb
============================ -->
<div class="head-title">
    <div class="left">
        <h1>Sản phẩm</h1>
        <ul class="breadcrumb">
            <li><a href="#">Sản phẩm</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Danh sách sản phẩm</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn-download">
		<span class="text">+ Thêm sản phẩm mới</span>
	</a>
</div>
@if(session('success'))
        <div class="alert alert-success" style="margin: 15px 0;">{{ session('success') }}</div>
    @endif
<!-- =========================
     Form lọc sản phẩm
============================ -->
<div class="body-content">
    <form action="{{ route('admin.products.index') }}" method="GET" class="filter-form" style="margin-bottom: 20px;">
        <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
            <div>
                <label for="keyword">Tìm kiếm:</label>
                <input type="text" name="keyword" id="keyword" value="{{ request('keyword') }}">
            </div>
            <div>
                <label for="brand">Thương hiệu:</label>
                <input type="text" name="brand" id="brand" value="{{ request('brand') }}">
            </div>
            <div>
                <label for="price_min">Giá từ:</label>
                <input type="number" name="price_min" id="price_min" value="{{ request('price_min') }}" min="0">
            </div>
            <div>
                <label for="price_max">Đến:</label>
                <input type="number" name="price_max" id="price_max" value="{{ request('price_max') }}" min="0">
            </div>
            <div>
                <label for="category">Danh mục:</label>
                <select name="category" id="category">
                    <option value="">Tất cả</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->Categories_ID }}" {{ request('category') == $cat->Categories_ID ? 'selected' : '' }}>
                            {{ $cat->Name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status">Trạng thái:</label>
                <select name="status" id="status">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>
            <div>
                <button type="submit" class="admin-form-loc">Lọc</button>
                <button type="submit" class="admin-form-loc"><a href="{{ route('admin.products.index') }}">Đặt lại</a></button>
            </div>

        </div>
    </form>

    <!-- =========================
         Bảng danh sách sản phẩm
    ============================ -->
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Giá KM</th>
                <th>Số lượng</th>
                <th>Thương hiệu</th>
                <th>Thuộc tính</th>
                <th>Mô tả</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @foreach($products as $key => $product)
            <tr>
                <td>{{ $key + 1 + ($products->currentPage() - 1) * $products->perPage() }}</td>
                <td>
                    @if ($product->Image)
                    <img src="{{ asset('uploads/products/' . $product->images) }}" width="60" height="60" alt="Hình sản phẩm">
                @else
                    <span>Không có ảnh</span>
                @endif
                </td>
                <td>{{ $product->Name }}</td>
                <td>{{ number_format($product->Price, 0, ',', '.') }}₫</td>
                <td>{{ number_format($product->Discount_price, 0, ',', '.') }}₫</td>
                <td>{{ $product->Quantity }}</td>
                <td>{{ $product->Brand }}</td>

                <!-- Hiển thị thuộc tính sản phẩm -->
                <td>
                    @foreach($product->variants as $variant)
                        @foreach($variant->values as $value)
                            @if($value->attribute)
                                <div><strong>{{ $value->attribute->Name }}:</strong> {{ $value->Value }}</div>
                            @endif
                        @endforeach
                    @endforeach
                </td>

                <td>{{ Str::limit($product->Description, 40) }}</td>
                <td>{{ $product->Status ? 'Hiển thị' : 'Ẩn' }}</td>
                <td class="action-buttons">
                    <!-- Nút Sửa -->
                    <button class="admin-button-table">
                        <a href="{{ route('admin.products.edit', $product->Product_ID) }}" style="display:block; width:100%; height:100%; color:inherit; text-decoration:none;">Sửa</a>
                    </button>

                    <!-- Nút Xóa -->
                    <form action="{{ route('admin.products.destroy', $product->Product_ID) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-button-table btn-delete" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">Xóa</button>
                    </form>
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- =========================
         Phân trang sản phẩm
    ============================ -->
    {{ $products->links() }}
</div>
@endsection
