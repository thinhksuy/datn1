@extends('layouts.layout')
@section('title', 'Danh sách đơn hàng')

@section('content')
<style>
form {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

form label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    color: #333;
}

form input[type="text"],
form input[type="date"],
form select {
    width: 180px;
    padding: 8px 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    background-color: #fff;
    transition: border 0.2s;
}

form input[type="text"]:focus,
form input[type="date"]:focus,
form select:focus {
    border-color: #3b82f6;
    outline: none;
}

</style>
<div class="head-title">
	<div class="left">
        <h1>Đơn hàng</h1>
        <ul class="breadcrumb">
            <li><a href="#">Đơn hàng</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Danh sách đơn hàng</a></li>
        </ul>
    </div>
</div>

<div class="body-content">
    @if (session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif
    <!-- =========================
     Bộ lọc đơn hàng
============================ -->
<form action="{{ route('admin.orders.index') }}" method="GET" style="margin-bottom: 20px;">
    <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
        <div>
            <label for="order_code">Mã đơn:</label>
            <input type="text" name="order_code" id="order_code" value="{{ request('order_code') }}">
        </div>

        <div>
            <label for="status">Trạng thái:</label>
            <select name="status" id="status">
                <option value="">Tất cả</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đã giao</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
            </select>
        </div>

        <div>
            <label for="date_from">Từ ngày:</label>
            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}">
        </div>

        <div>
            <label for="date_to">Đến ngày:</label>
            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}">
        </div>

        <div>
            <button type="submit" class="admin-form-loc">Lọc</button>
            <button type="submit" class="admin-form-loc"><a href="{{ route('admin.products.index') }}">Đặt lại</a></button>
        </div>
    </div>
</form>


    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Mã đơn hàng</th>
                <th>Khách hàng</th>
                <th>SĐT</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                <td>{{ $order->order_code }}</td>
                <td>{{ $order->user->Name ?? 'Không rõ' }}</td>
                <td>{{ $order->user->Phone ?? '---' }}</td>
                <td>{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                <td>
                    @switch($order->status)
    @case('pending') <span style="color: orange">Chờ xử lý</span> @break
    @case('confirmed') <span style="color: blue">Đã xác nhận</span> @break
    @case('shipping') <span style="color: darkcyan">Đang giao</span> @break
    @case('shipped') <span style="color: green">Đã giao</span> @break
    @case('completed') <span style="color: green">Hoàn thành</span> @break
    @case('cancelled') <span style="color: red">Đã hủy</span> @break
    @default <span>Không rõ</span>
@endswitch

                </td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td class="action-buttons">
                    <!-- Nút Xem -->
                    <button class="admin-button-table">
                        <a href="{{ route('admin.orders.show', $order->order_id) }}" style="display:block; width:100%; height:100%; color:inherit; text-decoration:none;">Xem</a>
                    </button>

                    <!-- Nút Sửa -->
                    <button class="admin-button-table">
                        <a href="{{ route('admin.orders.edit', $order->order_id) }}" style="display:block; width:100%; height:100%; color:inherit; text-decoration:none;">Sửa</a>
                    </button>
                </td>


            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Phân trang --}}

    {{ $orders->links() }}
</div>
@endsection
