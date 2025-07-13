@extends('layouts.layout')

@section('content')
<div class="head-title">
    <div class="left">
        <h1>Thống kê đơn hàng</h1>
        <ul class="breadcrumb">
            <li><a href="#">Thống kê</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Đơn hàng</a></li>
        </ul>
    </div>
</div>

<div class="body-content">
    <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
        <div class="card" style="background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <h4>Tổng đơn hàng</h4>
            <p style="font-size: 24px; font-weight: bold;">{{ $totalOrders }}</p>
        </div>
        <div class="card">
            <h4>Doanh thu</h4>
            <p style="font-size: 24px; font-weight: bold; color: green">{{ number_format($totalRevenue, 0, ',', '.') }}₫</p>
        </div>
        <div class="card">
            <h4>Đơn hoàn thành</h4>
            <p style="font-size: 24px; font-weight: bold; color: green">{{ $completedOrders }}</p>
        </div>
        <div class="card">
            <h4>Đơn đã hủy</h4>
            <p style="font-size: 24px; font-weight: bold; color: red">{{ $cancelledOrders }}</p>
        </div>
    </div>

    <div style="margin-top: 40px;">
        <h3>Doanh thu theo tháng</h3>
        <canvas id="revenueChart" height="100"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Doanh thu theo ngày',
                data: {!! json_encode($chartData) !!},
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.2)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 3,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + '₫';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: '#333'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toLocaleString('vi-VN') + '₫';
                        }
                    }
                }
            }
        }
    });
</script>

@endsection