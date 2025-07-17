@extends('layouts.layout')

@section('content')
{{-- Biểu đồ thống kê --}}
<h3 class="thongke left mt-5">📊 Thống Kê Doanh Thu & Đơn Hàng Theo Tháng</h3>
<div class="gant-chart" style="width:100%;max-width:1200px;margin:32px auto;">
    <canvas id="statChart"></canvas>
</div>

{{-- Bảng thống kê --}}
<br>
    <h4 class="thongke left mt-5">📋 Bảng Thống Kê Chi Tiết</h4>

<div class="body-statistics">
<div class="stat-table mt-5" style="width:100%;max-width:1200px;margin:0 auto;">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Tháng</th>
                <th>Lượt mua hàng</th>
                <th>Doanh thu (VNĐ)</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < 12; $i++)
                <tr>
                    <td>{{ $labels[$i] }}</td>
                    <td>{{ $orderCounts[$i] }}</td>
                    <td>{{ number_format($totalAmount[$i]) }}</td>
                </tr>
            @endfor
        </tbody>
    </table>
</div>

</div>
@endsection

@section('scripts')
{{-- Nhúng thư viện Chart.js --}}
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
