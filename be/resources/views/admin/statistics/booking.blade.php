@extends('layouts.layout')

@section('content')
<style>
    .compare-form {
        max-width: 1200px;
        background: #f9f9f9;
        padding: 20px 24px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    .compare-form .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .compare-form label {
        font-weight: 600;
        color: #333;
        font-size: 15px;
    }

    .compare-form select {
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        min-width: 120px;
    }

    .compare-form .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: flex-end;
    }

    .compare-form button {
        padding: 10px 20px;
        margin-left: 30px;
        font-weight: 600;
        border-radius: 8px;
        background-color: #007bff;
        border: none;
        color: #fff;
        transition: background 0.3s;
    }

    .compare-form button:hover {
        background-color: #0056b3;
    }
    .group-separator {
        width: 30px;
    }
</style>
{{-- Biểu đồ thống kê --}}
<div class="head-title">
        <div class="left">
            <h3 class="thongke left mt-5">📊 Thống Kê Đặt Sân Theo Tháng</h3>
        </div>
        <a href="{{ route('admin.statistics.booking') }}" class="btn-download">
            <span class="text">Quay lại</span>
	</a>
    </div>
<form action="{{ route('admin.statistics.booking') }}" method="GET" class="compare-form mb-4">
    <div class="form-row" style="display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-end;">

        {{-- Nhóm Tháng 1 + Năm 1 --}}
        <div class="form-group">
            <label>Tháng thứ nhất:</label>
            <select name="month1" required class="form-control">
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('month1') == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="form-group">
            <label>Năm thứ nhất:</label>
            <select name="year1" required class="form-control">
                @for ($i = now()->year; $i >= 2020; $i--)
                    <option value="{{ $i }}" {{ request('year1') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>

        {{-- Khoảng cách giữa 2 nhóm --}}
        <div class="group-separator" style="width: 30px;"></div>

        {{-- Nhóm Tháng 2 + Năm 2 --}}
        <div class="form-group">
            <label>Tháng thứ hai:</label>
            <select name="month2" required class="form-control">
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('month2') == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="form-group">
            <label>Năm thứ hai:</label>
            <select name="year2" required class="form-control">
                @for ($i = now()->year; $i >= 2020; $i--)
                    <option value="{{ $i }}" {{ request('year2') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>

        {{-- Nút so sánh --}}
        <div class="form-group">
            <button type="submit" class="btn btn-primary">So sánh</button>
        </div>
    </div>
</form>


<div class="gant-chart" style="width:100%;max-width:1200px;margin:32px auto;">
    <canvas id="statChart"></canvas>
</div>

{{-- Bảng thống kê lượt đặt sân --}}
<br>
    <h4 class="thongke left mt-5">📋 Bảng Thống Kê Đặt Sân</h4>

<div class="body-statistics">
<div class="stat-table mt-5" style="width:100%;max-width:1200px;margin:0 auto;">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Tháng</th>
                <th>Lượt đặt sân</th>
                <th>Doanh thu đặt sân (VNĐ)</th>
            </tr>
        </thead>
        <tbody>
    @foreach ($labels as $index => $label)
        <tr>
            <td>{{ $label }}</td>
            <td>{{ $courtBookingCounts[$index] }}</td>
            <td>{{ number_format($courtBookingRevenue[$index]) }}</td>
        </tr>
    @endforeach
</tbody>

    </table>
</div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('statChart').getContext('2d');
    const statChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Lượt đặt sân',
                    data: @json($courtBookingCounts),
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    yAxisID: 'y1',
                },
                {
                    label: 'Doanh thu đặt sân (VNĐ)',
                    data: @json($courtBookingRevenue),
                    backgroundColor: 'rgba(153, 102, 255, 0.7)',
                    yAxisID: 'y',
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: {
                    beginAtZero: true,
                    type: 'linear',
                    position: 'left',
                    title: { display: true, text: 'Doanh thu (VNĐ)' }
                },
                y1: {
                    beginAtZero: true,
                    type: 'linear',
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    title: { display: true, text: 'Số lượt đặt sân' }
                }
            }
        }
    });
</script>

@endsection
