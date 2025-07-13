@extends('layouts.layout') {{-- hoặc layout bạn đang dùng --}}

@section('title', 'Thêm bài viết')

@section('content')
<div class="head-title">
    <div class="left">
        <h1>Thêm bài viết</h1>
        <ul class="breadcrumb">
            <li><a href="#">Bài viết</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Thêm bài viết</a></li>
        </ul>
    </div>
    <a href="#" class="btn-download">
        <i class='bx bxs-cloud-download'></i>
        <span class="text">Download PDF</span>
    </a>
</div>

<div class="form-add">
    <h2>Thêm Bài Viết Mới</h2>
    <form action="#" method="POST" enctype="multipart/form-data">
        @csrf {{-- Laravel CSRF token --}}
        <div class="form-group">
            <label for="author">Người viết</label>
            <input type="text" id="author" name="author" required>
        </div>

        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="content-post">Nội dung</label>
            <textarea id="content-post" name="content" rows="6" required></textarea>
        </div>

        <div class="form-group">
            <label for="status">Trạng thái</label>
            <select id="status" name="status" required>
                <option value="hienthi">Hiển thị</option>
                <option value="an">Ẩn</option>
            </select>
        </div>

        <div class="form-group">
            <label for="image">Ảnh bài viết (không bắt buộc)</label>
            <input type="file" id="image" name="image" accept="image/*">
        </div>

        <div class="form-actions">
            <button type="submit">Thêm bài viết</button>
        </div>
    </form>
</div>
@endsection
