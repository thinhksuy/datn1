@extends('layouts.layout')

@section('content')
{{-- Biểu đồ thống kê --}}
<h3 class="thongke left mt-5">📊 Thống Kê Đặt Sân Theo Tháng</h3>
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
            @for ($i = 0; $i < 12; $i++)
                <tr>
                    <td>{{ $labels[$i] }}</td>
                    <td>{{ $courtBookingCounts[$i] }}</td>
                    <td>{{ number_format($courtBookingRevenue[$i]) }}</td>
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
