@extends('layouts.layout')

@section('content')
<div class="head-title">
    <div class="left">
        <h1>Tài khoản</h1>
        <ul class="breadcrumb">
            <li><a href="#">Tài khoản</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Danh sách tài khoản</a></li>
        </ul>
    </div>
    <a href="#" class="btn-download">
        <i class='bx bxs-cloud-download'></i>
        <span class="text">Download PDF</span>
    </a>
</div>

<div class="body-content">
    {{-- Bộ lọc --}}
    <form action="{{ route('admin.users.index') }}" method="GET" style="margin-bottom: 20px;">
        <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
            <div>
                <label for="keyword">Tìm kiếm:</label>
                <input type="text" name="keyword" id="keyword" value="{{ request('keyword') }}">
            </div>

            <div>
                <label for="role">Vai trò:</label>
                <select name="role" id="role">
                    <option value="">Tất cả</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->Role_ID }}" {{ request('role') == $role->Role_ID ? 'selected' : '' }}>
                            {{ $role->Name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status">Trạng thái:</label>
                <select name="status" id="status">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Kích hoạt</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Tạm khóa</option>
                </select>
            </div>

            <div>
                <button type="submit">Lọc</button>
                <a href="{{ route('admin.users.index') }}" style="margin-left: 10px;">Đặt lại</a>
            </div>
        </div>
    </form>

    @if (session('success'))
        <div style="color: green">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Ảnh</th>
                <th>Tên</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Vai trò</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 + ($users->currentPage() - 1) * $users->perPage() }}</td>
                    <td><img src="{{ asset($user->Avatar ?? 'WebAdmin/img/people.png') }}" alt="Ảnh" width="50"></td>
                    <td>{{ $user->Name }}</td>
                    <td>{{ $user->Email }}</td>
                    <td>{{ $user->Phone }}</td>
                    <td>{{ $user->role->Name ?? 'Không rõ' }}</td>
                    <td>
                        <form action="{{ route('admin.users.destroy', $user->ID) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</button>
                        </form>
                        <a href="{{ route('admin.users.edit', $user->ID) }}">
                            <button>Sửa</button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px">
        {{ $users->appends(request()->query())->links() }}
    </div>
</div>
@endsection
