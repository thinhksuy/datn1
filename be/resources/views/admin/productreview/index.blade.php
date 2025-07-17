@extends('layouts.layout')

@section('content')

<div class="head-title">
    <div class="left">
        <h1>Bình luận sản phẩm</h1>
        <ul class="breadcrumb">
            <li><a href="#">Bình luận</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Danh sách</a></li>
        </ul>
    </div>
</div>
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


<div class="body-content">
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Đánh giá</th>
                <th>Người dùng</th>
                <th>Sản phẩm</th>
                <th>Đơn hàng</th>
                <th>Bình luận</th>
                <th>Hình ảnh</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reviews as $index => $review)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $review->Rating }}/5</td>
                    <td>{{ $review->user->Name ?? 'N/A' }}</td>
                    <td>{{ $review->product->Name ?? 'N/A' }}</td>
                    <td>#{{ $review->order->order_code ?? 'N/A' }}</td>
                    <td>{{ $review->Comment }}</td>
                    <td>
                        @if($review->Image)
                            <img src="{{ asset('storage/'.$review->Image) }}" alt="Image" width="50">
                        @else
                            Không có
                        @endif
                    </td>
                    <td class="select-status">
                        <form action="{{ route('admin.comments.product.toggleStatus', $review->Product_review_ID) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <select name="status"
                                    class="{{ $review->Status ? 'status-show' : 'status-hide' }}"
                                    onchange="this.form.submit()">
                                <option value="1" {{ $review->Status ? 'selected' : '' }}>Hiển thị</option>
                                <option value="0" {{ !$review->Status ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </form>
                    </td>



                    <td class="action-buttons">
                        <!-- Nút Xem -->
                        <button class="admin-button-table">
                            <a href="{{ route('admin.comments.product.show', $review->Product_review_ID) }}" style="display:block; width:100%; height:100%; color:inherit; text-decoration:none;">Xem</a>
                        </button>

                        <!-- Nút Xóa -->
                        <form action="{{ route('admin.comments.product.destroy', $review->Product_review_ID) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-button-table btn-delete" onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</button>
                        </form>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $reviews->links() }}
</div>
@endsection
