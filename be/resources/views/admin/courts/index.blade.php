@extends('layouts.layout')

@section('title', 'Quản lý sân cầu lông')

@section('content')
<style>
    .container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 16px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .header h1 {
        font-size: 24px;
        font-weight: bold;
        color: #2d3748;
    }

    .btn-add {
        background-color: #2563eb;
        color: white;
        padding: 10px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .btn-add:hover {
        background-color: #1d4ed8;
    }

    .table-container {
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    thead {
        background-color: #f3f4f6;
        text-transform: uppercase;
        font-size: 12px;
        color: #4b5563;
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
    }

    tbody tr:hover {
        background-color: #f9fafb;
    }

    .image-thumb {
        width: 100px;
        height: 70px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #ddd;
    }

    .status-active {
        background-color: #d1fae5;
        color: #065f46;
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 9999px;
        display: inline-block;
    }

    .status-inactive {
        background-color: #fee2e2;
        color: #991b1b;
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 9999px;
        display: inline-block;
    }

    .action-links a {
        margin-right: 8px;
        color: #2563eb;
        text-decoration: none;
    }

    .action-links a:hover {
        text-decoration: underline;
    }

    .action-links form {
        display: inline-block;
    }

    .action-links button {
        background: none;
        border: none;
        color: #dc2626;
        cursor: pointer;
    }

    .action-links button:hover {
        text-decoration: underline;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .text-muted {
        color: #6b7280;
    }
</style>

<div class="container">
    <div class="header">
        <h1>Danh sách sân cầu lông</h1>
        <a href="{{ route('admin.courts.create') }}" class="btn-add">+ Thêm sân mới</a>
    </div>

    <div class="table-container">
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
                        <td class="text-center action-links">
                            <a href="{{ route('admin.courts.edit', $court->Courts_ID) }}">Sửa</a>
                            <form action="{{ route('admin.courts.destroy', $court->Courts_ID) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sân này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Xóa</button>
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
    </div>
</div>
@endsection
