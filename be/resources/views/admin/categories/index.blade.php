@extends('layouts.layout')

@section('title', 'Danh sách danh mục')

@section('content')
<div class="head-title">
	<div class="left">
		<h1>Danh mục sản phẩm</h1>
		<ul class="breadcrumb">
			<li><a href="#">Danh mục sản phẩm</a></li>
			<li><i class='bx bx-chevron-right'></i></li>
			<li><a class="active" href="#">Danh sách danh mục</a></li>
		</ul>
	</div>
	<a href="{{ route('admin.categories.create') }}" class="btn-download">
		<span class="text">+ Thêm danh mục mới</span>
	</a>
</div>
@if(session('success'))
        <div class="alert alert-success" style="margin: 15px 0;">{{ session('success') }}</div>
    @endif
<div class="body-content">
	<table>
		<thead>
			<tr>
				<th>STT</th>
				<th>Ảnh</th>
				<th>Tên danh mục</th>
				<th>Mô tả</th>
				<th>Thao Tác</th>
			</tr>
		</thead>
		<tbody>
    @foreach($categories as $index => $category)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>
                @if($category->Image)
                    <img src="{{ asset($category->Image) }}" width="50">
                @else
                    <img src="{{ asset('WebAdmin/img/default.png') }}" width="50">
                @endif
            </td>
            <td>{{ $category->Name }}</td>
            <td>{{ $category->Description }}</td>
            <td class="action-buttons">
                <!-- Nút Sửa -->
                <button class="admin-button-table">
                    <a href="{{ route('admin.categories.edit', $category->Categories_ID) }}" style="display: block; width: 100%; height: 100%; color: inherit; text-decoration: none;">Sửa</a>
                </button>

                <!-- Nút Xoá -->
                <form action="{{ route('admin.categories.destroy', $category->Categories_ID) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-button-table btn-delete" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                </form>
            </td>

        </tr>
    @endforeach
</tbody>

	</table>
    {{ $categories->links() }}
</div>
@endsection
