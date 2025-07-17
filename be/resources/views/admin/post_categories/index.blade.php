@extends('layouts.layout') {{-- Giả sử bạn có layout admin --}}

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Danh Mục Bài Viết</h1>
            <ul class="breadcrumb">
                <li><a href="#">Bài viết</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Danh sách danh mục</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.post_categories.create') }}" class="btn-download">
		<span class="text">+ Thêm danh mục mới</span>
	</a>
    </div>

    <div class="body-content">
        <h2>Quản lý Danh Mục</h2>

        {{-- Hiển thị thông báo thành công --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Trạng thái</th>
                    <th>Tên danh mục</th>
                    <th>Mô tả</th>
                    <th>Lượt xem</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $index => $category)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $category->Status ? 'Hiển thị' : 'Ẩn' }}</td>
                    <td>{{ $category->Title }}</td>
                    <td>{{ $category->Content }}</td>
                    <td>{{ $category->View }}</td>
                    <td>{{ \Carbon\Carbon::parse($category->Created_at)->format('d-m-Y') }}</td>
                    <td class="action-buttons">
                        <!-- Nút Sửa -->
                        <button class="admin-button-table">
                            <a href="{{ route('admin.post_categories.edit', $category->Post_Categories_ID) }}" style="display: block; width: 100%; height: 100%; color: inherit; text-decoration: none;">Sửa</a>
                        </button>

                        <!-- Nút Xoá -->
                        <form action="{{ route('admin.post_categories.destroy', $category->Post_Categories_ID) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-button-table btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xoá</button>
                        </form>
                    </td>

                </tr>
                @endforeach

                @if ($categories->isEmpty())
                <tr>
                    <td colspan="7" style="text-align: center;">Không có danh mục nào.</td>
                </tr>
                @endif
            </tbody>
        </table>
        {{ $categories->links() }}
    </div>
</main>
@endsection
