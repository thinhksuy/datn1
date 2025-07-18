@extends('layouts.layout')

@section('content')
<div class="head-title">
    <div class="left">
        <h1>Chi tiết đơn hàng</h1>
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Chi tiết</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn-download">
                    <span class="text">Quay lại</span>
                </a>
</div>

{{-- Thông tin đơn hàng --}}
<div class="order-info card" style="padding: 20px; background: #fff; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
    <h3 style="margin-bottom: 20px;">🧾 Thông tin đơn hàng</h3>
    <div class="info-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
        <p><strong>Mã đơn hàng:</strong> {{ $order->order_code }}</p>
        <p><strong>Khách hàng:</strong> {{ $order->user->Name ?? 'Ẩn danh' }}</p>
        <p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
        <p><strong>Ghi chú:</strong> {{ $order->note_user ?? 'Không có' }}</p>
        <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</p>
        <p><strong>Phí vận chuyển:</strong> {{ number_format($order->shiping_fee, 0, ',', '.') }}₫</p>
        <p><strong>Tổng tiền:</strong> <span style="color: #d32f2f">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span></p>
        <p><strong>Trạng thái:</strong>
            <span style="color: {{ $order->status === 'completed' ? 'green' : ($order->status === 'cancelled' ? 'red' : '#FFA500') }}">
                {{ ucfirst($order->status) }}
            </span>
        </p>
    </div>
</div>

{{-- Danh sách sản phẩm --}}
<div class="order-details card" style="padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
    <h3 style="margin-bottom: 20px;">📦 Sản phẩm trong đơn hàng</h3>
    <table style="width: 100%; border-collapse: collapse;">
        <thead style="background-color: #f5f5f5;">
            <tr>
                <th style="padding: 10px; border-bottom: 1px solid #ddd;">STT</th>
                <th style="padding: 10px; border-bottom: 1px solid #ddd;">Tên sản phẩm</th>
                <th style="padding: 10px; border-bottom: 1px solid #ddd;">SKU</th>
                <th style="padding: 10px; border-bottom: 1px solid #ddd;">Giá</th>
                <th style="padding: 10px; border-bottom: 1px solid #ddd;">Số lượng</th>
                <th style="padding: 10px; border-bottom: 1px solid #ddd;">Thành tiền</th>
                {{-- <th style="padding: 10px; border-bottom: 1px solid #ddd;">Thao tác</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($order->details as $index => $detail)
                <tr style="text-align: center;">
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $index + 1 }}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $detail->product_name }}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $detail->SKU }}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ number_format($detail->price, 0, ',', '.') }}₫</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $detail->quantity }}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ number_format($detail->total, 0, ',', '.') }}₫</td>
                    {{-- <td style="padding: 10px; border-bottom: 1px solid #eee;">
    <form action="{{ route('admin.order-details.destroy', $detail->order_detail_id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xoá sản phẩm này khỏi đơn hàng?');">
        @csrf
        @method('DELETE')
        <button type="submit" style="color: red; background: none; border: none; cursor: pointer;">Xoá</button>
    </form>
</td> --}}

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
