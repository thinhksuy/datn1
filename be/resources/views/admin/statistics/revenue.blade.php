@extends('layouts.layout')

@section('content')
<h3 class="thongke left mt-5">📊 Thống Kê Doanh Thu Tổng Hợp Theo Tháng</h3>

<div class="gant-chart" style="width:100%;max-width:1200px;margin:32px auto;">
    <canvas id="revenueChart"></canvas>
</div>

<br>
<h4 class="thongke left mt-5">📋 Bảng Thống Kê Doanh Thu Chi Tiết</h4>

<div class="body-statistics">
    <div class="stat-table mt-4" style="width:100%;max-width:1200px;margin:0 auto;">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Tháng</th>
                    <th>Doanh thu đơn hàng (VNĐ)</th>
                    <th>Doanh thu đặt sân (VNĐ)</th>
                    <th>Tổng doanh thu (VNĐ)</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 12; $i++)
                    <tr>
                        <td>{{ $labels[$i] }}</td>
                        <td>{{ number_format($orderRevenue[$i]) }}</td>
                        <td>{{ number_format($bookingRevenue[$i]) }}</td>
                        <td>{{ number_format($totalRevenue[$i]) }}</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Doanh thu đơn hàng (VNĐ)',
                    data: @json($orderRevenue),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    stack: 'combined',
                    yAxisID: 'y'
                },
                {
                    label: 'Doanh thu đặt sân (VNĐ)',
                    data: @json($bookingRevenue),
                    backgroundColor: 'rgba(255, 206, 86, 0.7)',
                    stack: 'combined',
                    yAxisID: 'y'
                },
                {
                    label: 'Tổng doanh thu (VNĐ)',
                    data: @json($totalRevenue),
                    type: 'line',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false,
                    yAxisID: 'y'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Doanh thu (VNĐ)' }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>
@endsection
