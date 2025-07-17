@extends('layouts.layout')

@section('content')
<main>
    <div class="head-title">
    <div class="left">
        <h1>Chỉnh sửa voucher</h1>
        <ul class="breadcrumb">
            <li><a href="#">Voucher</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Chỉnh sửa voucher</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.vouchers.index') }}" class="btn-download">
        <span class="text">Quay lại</span>
    </a>
</div>

    <div class="form-add">
        <h2>Cập nhật thông tin</h2>

        <form action="{{ route('admin.vouchers.update', $voucher->Vouchers_ID) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="code">Mã Voucher</label>
                <input type="text" id="code" name="code" value="{{ old('code', $voucher->Code) }}" required>
            </div>

            <div class="form-group">
                <label for="discount_type">Loại giảm giá</label>
                <select id="discount_type" name="discount_type" required>
                    <option value="percentage" {{ $voucher->Discount_type == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
                    <option value="fixed" {{ $voucher->Discount_type == 'fixed' ? 'selected' : '' }}>Cố định</option>
                </select>
            </div>

            <div class="form-group">
                <label for="discount_value">Giá trị giảm</label>
                <input type="number" step="0.01" id="discount_value" name="discount_value" value="{{ old('discount_value', $voucher->Discount_value) }}" required>
            </div>

            <div class="form-group">
                <label for="max_uses">Lượt dùng tối đa</label>
                <input type="number" id="max_uses" name="max_uses" value="{{ old('max_uses', $voucher->Max_uses) }}">
            </div>

            <div class="form-group">
                <label for="expires">Ngày hết hạn</label>
                <input type="date" id="expires" name="expires" value="{{ old('expires', $voucher->Expires) }}">
            </div>

            <div class="form-group">
                <label for="applies_to">Áp dụng cho</label>
                <input type="text" id="applies_to" name="applies_to" value="{{ old('applies_to', $voucher->applies_to) }}">
            </div>

            <div class="form-actions">
                <button type="submit">Cập nhật Voucher</button>
            </div>
        </form>
    </div>
</main>
@endsection
