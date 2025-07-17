@extends('layouts.layout')

@section('title', 'Quản lý sân cầu lông')

@section('content')
<div class="head-title">
				<div class="left">
					<h1>Danh sách sân</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Quản lí sân</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Danh sách  sân</a>
						</li>
					</ul>
				</div>
				<a href="{{ route('admin.courts.create') }}" class="btn-download">
                    <span class="text">+ Thêm sân mới</span>
                </a>
			</div>

<div class="body-content">
    <div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Hình ảnh</th>
                    <th>Tên sân</th>
                    <th>Vị trí</th>
                    <th>Loại</th>
                    <th class="text-right">Giá/giờ</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courts as $index => $court)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <img src="{{ $court->Image ? asset('storage/' . $court->Image) : 'https://via.placeholder.com/100x70?text=No+Image' }}"
                                 class="image-thumb" alt="Court Image">
                        </td>
                        <td>{{ $court->Name }}</td>
                        <td>{{ $court->Location }}</td>
                        <td>{{ $court->Court_type }}</td>
                        <td class="text-right">{{ number_format($court->Price_per_hour, 0, ',', '.') }} đ</td>
                        <td class="text-center">
                            @if($court->Status)
                                <span class="status-active">Hoạt động</span>
                            @else
                                <span class="status-inactive">Tạm ngưng</span>
                            @endif
                        </td>
                        <td class="action-buttons text-center">
                            <!-- Nút Sửa -->
                            <button class="admin-button-table">
                                <a href="{{ route('admin.courts.edit', $court->Courts_ID) }}" style="display:block; width:100%; height:100%; color:inherit; text-decoration:none;">Sửa</a>
                            </button>

                            <!-- Nút Xoá -->
                            <form action="{{ route('admin.courts.destroy', $court->Courts_ID) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-button-table btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa sân này?')">Xoá</button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Chưa có sân nào được tạo.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $courts->links() }}
    </div>
</div>
@endsection
