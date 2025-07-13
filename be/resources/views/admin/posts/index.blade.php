@extends('layouts.layout') {{-- Layout tổng --}}

@section('title', 'Danh sách bài viết')

@section('content')
<div class="head-title">
    <div class="left">
        <h1>Bài viết</h1>
        <ul class="breadcrumb">
            <li>
                <a href="#">Bài viết</a>
            </li>
            <li><i class='bx bx-chevron-right' ></i></li>
            <li>
                <a class="active" href="#">Danh sách bài viết</a>
            </li>
        </ul>
    </div>
    <a href="#" class="btn-download">
        <i class='bx bxs-cloud-download' ></i>
        <span class="text">Download PDF</span>
    </a>
</div>

<div class="body-content">
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Trạng thái</th>
                <th>Tiêu đề</th>
                <th>Nội dung</th>
                <th>Người viết</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <!--  -->
        </tbody>
    </table>
</div>
@endsection
