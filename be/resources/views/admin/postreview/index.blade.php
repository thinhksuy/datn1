@extends('layouts.layout')

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
                <th>Trạng thái</th>
                <th>Người bình luận</th>
                <th>Nội dung</th>
                <th>Bài viết</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
@foreach($comments as $index => $comment)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td class="select-status">
            <form action="{{ route('admin.comments.post.updateStatus', $comment->Comment_ID) }}" method="POST">

                @csrf
                @method('PATCH')
                <select name="status" class="{{ $comment->Status ? 'status-show' : 'status-hide' }}" onchange="this.form.submit()" style="cursor: pointer;">
                    <option value="0" {{ !$comment->Status ? 'selected' : '' }}>Đã ẩn</option>
                    <option value="1" {{ $comment->Status ? 'selected' : '' }}>Đã duyệt</option>
                </select>
            </form>
        </td>


        <td>{{ $comment->user->Name ?? 'Không xác định' }}</td>
        <td>{{ $comment->Content }}</td>
        <td>{{ $comment->post->Title ?? 'Không có tiêu đề' }}</td>

        <td class="action-buttons">
            <!-- Nút Xem -->
            {{-- <button class="admin-button-table">
                <a href="{{ route('comments.show', $comment->Comment_ID) }}" style="display:block; width:100%; height:100%; color:inherit; text-decoration:none;">Xem</a>
            </button> --}}

            <!-- Nút Xoá -->
            <form action="{{ route('comments.destroy', $comment->Comment_ID) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-button-table btn-delete" onclick="return confirm('Xóa bình luận này?')">Xóa</button>
            </form>
        </td>

    </tr>
@endforeach
</tbody>

    </table>
    {{ $comments->links() }}
</div>
@endsection
