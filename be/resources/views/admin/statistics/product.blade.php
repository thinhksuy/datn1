@extends('layouts.layout')

@section('content')
{{-- Biểu đồ thống kê --}}
<h3 class="thongke left mt-5">📈 Biểu Đồ Sản Phẩm Bán Chạy</h3>
<div class="gant-chart" style="width:100%;max-width:1200px;margin:32px auto;">
    <canvas id="productChart"></canvas>
</div>

{{-- Bảng thống kê --}}
<br>
        <h4 class="thongke left mt-5">📦 Bảng Thống Kê Sản Phẩm Bán Chạy</h4>
<div class="body-statistics">

    <div class="stat-table mt-5" style="width:100%;max-width:1200px;margin:0 auto;">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng bán</th>
                    <th>Tổng doanh thu (VNĐ)</th>
                </tr>
            </thead>
        <tbody>
            @foreach ($stats as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->total_sold }}</td>
                    <td>{{ number_format($item->total_revenue) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
@endsection

@section('scripts')
{{-- Nhúng thư viện Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctxProduct = document.getElementById('productChart').getContext('2d');

new Chart(ctxProduct, {
    type: 'bar',
    data: {
        labels: @json($productNames), // Trục X: Tên sản phẩm
        datasets: [
            {
                label: 'Số lượng bán',
                data: @json($productSales),
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                yAxisID: 'y',
            },
            {
                label: 'Doanh thu (VNĐ)',
                data: @json($productRevenue),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        indexAxis: 'x', // Biểu đồ cột dọc (default)
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        scales: {
            y: {
                beginAtZero: true,
                position: 'left',
                title: { display: true, text: 'Số lượng' }
            },
            y1: {
                beginAtZero: true,
                position: 'right',
                grid: { drawOnChartArea: false },
                title: { display: true, text: 'Doanh thu (VNĐ)' }
            }
        },
        plugins: {
            legend: {
                position: 'top',
            },
        }
    }
});
</script>
@endsection

