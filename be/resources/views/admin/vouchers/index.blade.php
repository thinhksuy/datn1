@extends('layouts.layout')

@section('content')
<div class="head-title">
    <div class="left">
        <h1>Voucher</h1>
        <ul class="breadcrumb">
            <li><a href="#">Voucher</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Danh sách voucher</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.vouchers.create') }}" class="btn-download">
        <span class="text">+ Thêm voucher mới</span>
    </a>
</div>

<div class="body-content">
    <h1>Quản lý Voucher</h1>
    @if (session('success'))
    <div class="alert alert-success" corlo>
        {{ session('success') }}
    </div>
@endif
 @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Loại giảm</th>
                <th>Giá trị</th>
                <th>Mã</th>
                <th>Giới hạn sử dụng</th>
                <th>Hạn dùng</th>
                <th>Áp dụng cho</th>
                <th>Hành động</th>
            </tr>
        </thead>

        <tbody>
    @forelse ($vouchers as $voucher)
        <tr>
            <td>{{ $loop->iteration + ($vouchers->currentPage() - 1) * $vouchers->perPage() }}</td>
            <td>{{ $voucher->Discount_type == 'percentage' ? 'Phần trăm' : 'Cố định' }}</td>
            <td>
                {{ $voucher->Discount_type == 'percentage'
                    ? $voucher->Discount_value . '%'
                    : number_format($voucher->Discount_value) . '₫' }}
            </td>
            <td>{{ $voucher->Code }}</td>
            <td>{{ $voucher->Max_uses ?? 'Không giới hạn' }}</td>
            <td>{{ $voucher->Expires ?? 'Không có' }}</td>
            <td>{{ $voucher->applies_to ?? 'Không rõ' }}</td>
            <td class="action-buttons">
                <!-- Nút Sửa -->
                <button class="admin-button-table">
                    <a href="{{ route('admin.vouchers.edit', $voucher->Vouchers_ID) }}" class="btn-edit" style="display: block; width: 100%; height: 100%; color: inherit; text-decoration: none;">Sửa</a>
                </button>

                <!-- Nút Xoá giống y hệt nút Sửa -->
                <form action="{{ route('admin.vouchers.destroy', $voucher->Vouchers_ID) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-button-table btn-delete" onclick="return confirm('Bạn có chắc muốn xoá?')">Xoá</button>
                </form>
            </td>

        </tr>
    @empty
        <tr>
            <td colspan="8">Chưa có voucher nào.</td>
        </tr>
    @endforelse
</tbody>
</table>

<div style="margin-top: 20px">
        {{ $vouchers->appends(request()->query())->links() }}
    </div>

    </table>
    {{ $vouchers->links() }}

</div>
@endsection
