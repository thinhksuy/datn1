@extends('layouts.layout')

@section('content')
<div class="head-title">
    <div class="left">
        <h1>Dashboard</h1>
        <ul class="breadcrumb">
            <li><a href="#">Dashboard</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Trang chủ</a></li>
        </ul>
    </div>
    <a href="#" class="btn-download">
        <i class='bx bxs-cloud-download'></i>
        <span class="text">Tải báo cáo PDF</span>
    </a>
</div>

{{-- Box thống kê nhanh --}}
<ul class="box-info">
    <li>
        <i class='bx bxs-calendar-check'></i>
        <span class="text">
            <h3>{{ $orderThisMonth }}</h3>
            <p>Đơn hàng mới</p>
        </span>
    </li>
    <li>
        <i class='bx bxs-group'></i>
        <span class="text">
            <h3>{{ $newUsers }}</h3>
            <p>Khách hàng mới</p>
        </span>
    </li>
    <li>
        <i class='bx bxs-dollar-circle'></i>
        <span class="text">
            <h3>{{ number_format($revenueThisMonth, 0, ',', '.') }}đ</h3>
            <p>Tổng doanh thu tháng</p>
        </span>
    </li>
    <li>
        <i class='bx bxs-calendar'></i>
        <span class="text">
            <h3>{{ $newCourtBookings }}</h3>
            <p>Lịch đặt sân mới</p>
        </span>
    </li>
</ul>


{{-- Biểu đồ thống kê --}}
<h3 class="thongke left mt-5">📊 Thống Kê Doanh Thu & Đơn Hàng Theo Tháng</h3>
<div class="gant-chart" style="width:100%;max-width:1200px;margin:32px auto;">
    <canvas id="statChart"></canvas>
</div>
@endsection

@section('scripts')
{{-- Nhúng thư viện Chart.js nếu chưa có --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('statChart').getContext('2d');
    const statChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Tổng tiền (VNĐ)',
                    data: @json($totalAmount),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    yAxisID: 'y',
                },
                {
                    label: 'Lượt mua hàng',
                    data: @json($orderCounts),
                    backgroundColor: 'rgba(255, 159, 64, 0.7)',
                    yAxisID: 'y1',
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
                    title: { display: true, text: 'Tổng tiền (VNĐ)' }
                },
                y1: {
                    beginAtZero: true,
                    type: 'linear',
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    title: { display: true, text: 'Số lượt (Lần)' }
                }
            }
        }
    });
</script>
@endsection
