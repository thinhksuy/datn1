@extends('layouts.layout')

@section('content')
<div class="head-title">
    <div class="left">
        <h1>Thống kê sản phẩm</h1>
        <form method="GET" class="filter-form" style="margin-bottom: 20px;">
            Từ ngày:
            <input type="date" name="from_date" value="{{ $from }}">
            Đến ngày:
            <input type="date" name="to_date" value="{{ $to }}">
            <button type="submit">Lọc</button>
        </form>
    </div>
</div>

<div class="chart-container" style="width: 100%; max-width: 800px; margin-bottom: 40px;">
    <canvas id="productChart"></canvas>
</div>

<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Tên sản phẩm</th>
            <th>Số lượng đã bán</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $index => $product)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->total_sold ?? 0 }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('productChart').getContext('2d');
    const productChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($products->pluck('name')) !!},
            datasets: [{
                label: 'Số lượng bán',
                data: {!! json_encode($products->pluck('total_sold')) !!},
                backgroundColor: '#36a2eb'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
