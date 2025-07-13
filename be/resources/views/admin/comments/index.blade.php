@extends('layouts.layout') {{-- Thay bằng layout chính của bạn nếu khác --}}

@section('title', 'Bình luận bài viết')

@section('content')
<div class="head-title">
    <div class="left">
        <h1>Bình luận bài viết</h1>
        <ul class="breadcrumb">
            <li><a href="#">Bình luận</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Bình luận bài viết</a></li>
        </ul>
    </div>
    <a href="#" class="btn-download">
        <i class='bx bxs-cloud-download'></i>
        <span class="text">Download PDF</span>
    </a>
</div>

<div class="body-content">
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Người bình luận</th>
                <th>Nội dung</th>
                <th>Bài viết</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            {{-- Đây là ví dụ cứng. Sau này bạn sẽ dùng vòng lặp @foreach --}}
            <tr>
                <td>1</td>
                <td>Nguyễn Văn A</td>
                <td>Bài viết rất hữu ích, cảm ơn tác giả!</td>
                <td>Cách chăm sóc sức khỏe mùa hè</td>
                <td>
                    <select>
                        <option>Chờ duyệt</option>
                        <option>Đã duyệt</option>
                        <option>Ẩn</option>
                    </select>
                </td>
                <td>
                    <button>Xem</button> | <button>Xóa</button>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Trần Thị B</td>
                <td>Thông tin hữu ích, mong có thêm nhiều bài tương tự.</td>
                <td>Bí quyết học lập trình hiệu quả</td>
                <td>
                    <select>
                        <option selected>Đã duyệt</option>
                        <option>Chờ duyệt</option>
                        <option>Ẩn</option>
                    </select>
                </td>
                <td>
                    <button>Xem</button> | <button>Xóa</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
