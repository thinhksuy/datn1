@extends('layouts.layout')

@section('title', 'Lịch đặt sân')

@section('content')
<style>
    .booking-container {
        max-width: 1100px;
        margin: 30px auto;
        background: #fff;
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 24px;
    }

    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .booking-header h2 {
        font-size: 24px;
        color: #1f2937;
    }

    .btn-add {
        background-color: #2563eb;
        color: #fff;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        font-weight: bold;
        text-decoration: none;
    }

    .btn-add:hover {
        background-color: #1e40af;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background-color: #f3f4f6;
    }

    th, td {
        padding: 12px 16px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
    }

    th {
        font-size: 14px;
        color: #374151;
    }

    td {
        font-size: 15px;
        color: #111827;
    }

    .status-active {
        background-color: #d1fae5;
        color: #065f46;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 13px;
        display: inline-block;
    }

    .status-cancelled {
        background-color: #fee2e2;
        color: #b91c1c;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 13px;
        display: inline-block;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .action-buttons form {
        display: inline;
    }

    .action-buttons a,
    .action-buttons button {
        font-size: 14px;
        text-decoration: none;
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .action-edit {
        color: #2563eb;
    }

    .action-edit:hover {
        background: #ebf4ff;
    }

    .action-delete {
        color: #dc2626;
    }

    .action-delete:hover {
        background: #fee2e2;
    }
</style>

<div class="booking-container">
    <div class="booking-header">
        <h2>Danh sách lịch đặt sân</h2>
        <a href="{{ route('admin.bookings.create') }}" class="btn-add">+ Thêm lịch đặt</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Khách hàng</th>
                <th>Sân</th>
                <th>Ngày</th>
                <th>Giờ</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $index => $booking)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $booking->user->name ?? $booking->customer_name }}</td>
                    <td>{{ $booking->court->Name ?? 'Không rõ' }}</td>
                    <td>{{ $booking->Booking_date }}</td>
                    <td>{{ $booking->Start_time }} - {{ $booking->End_time }}</td>
                    <td>{{ number_format($booking->Total_price) }}đ</td>
                    <td>
                        @if($booking->Status)
                            <span class="status-active">Hoạt động</span>
                        @else
                            <span class="status-cancelled">Đã huỷ</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.bookings.edit', $booking) }}" class="action-edit">Sửa</a>
                            <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xoá?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-delete">Xoá</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Không có lịch đặt sân nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
