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

<div class="head-title">
        <div class="left">
            <h3 class="thongke left mt-5">📊 Thống kê doanh thu theo tháng</h3>
        </div>
        <a href="{{ route('admin.statistics.revenue') }}" class="btn-download">
            <span class="text">Quay lại</span>
	</a>
    </div>
<form action="{{ route('admin.statistics.revenue') }}" method="GET" class="compare-form mb-4">
    <div class="form-row">
        {{-- Nhóm Tháng 1 + Năm 1 --}}
        <div class="form-group">
            <label>Tháng thứ nhất:</label>
            <select name="month1" required>
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('month1') == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="form-group">
            <label>Năm thứ nhất:</label>
            <select name="year1" required>
                @for ($i = now()->year; $i >= 2020; $i--)
                    <option value="{{ $i }}" {{ request('year1') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>

        {{-- Khoảng cách giữa hai nhóm --}}
        <div class="group-separator"></div>

        {{-- Nhóm Tháng 2 + Năm 2 --}}
        <div class="form-group">
            <label>Tháng thứ hai:</label>
            <select name="month2" required>
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('month2') == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="form-group">
            <label>Năm thứ hai:</label>
            <select name="year2" required>
                @for ($i = now()->year; $i >= 2020; $i--)
                    <option value="{{ $i }}" {{ request('year2') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="form-group">
            <button type="submit">So sánh</button>
        </div>
    </div>
</form>




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
                @if ($compareMode)
                    @for ($i = 0; $i < count($compareLabels); $i++)
                        <tr>
                            <td>{{ $compareLabels[$i] }}</td>
                            <td>{{ number_format($compareOrderRevenue[$i]) }}</td>
                            <td>{{ number_format($compareBookingRevenue[$i]) }}</td>
                            <td>{{ number_format($compareTotalRevenue[$i]) }}</td>
                        </tr>
                    @endfor
                @else
                    @for ($i = 0; $i < count($labels); $i++)
                        <tr>
                            <td>{{ $labels[$i] }}</td>
                            <td>{{ number_format($orderRevenue[$i]) }}</td>
                            <td>{{ number_format($bookingRevenue[$i]) }}</td>
                            <td>{{ number_format($totalRevenue[$i]) }}</td>
                        </tr>
                    @endfor
                @endif
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');

    const isCompareMode = {{ $compareMode ? 'true' : 'false' }};

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: isCompareMode ? @json($compareLabels) : @json($labels),
            datasets: [
                {
                    label: 'Doanh thu đơn hàng (VNĐ)',
                    data: isCompareMode ? @json($compareOrderRevenue) : @json($orderRevenue),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    stack: 'combined',
                    yAxisID: 'y'
                },
                {
                    label: 'Doanh thu đặt sân (VNĐ)',
                    data: isCompareMode ? @json($compareBookingRevenue) : @json($bookingRevenue),
                    backgroundColor: 'rgba(255, 206, 86, 0.7)',
                    stack: 'combined',
                    yAxisID: 'y'
                },
                {
                    label: 'Tổng doanh thu (VNĐ)',
                    data: isCompareMode ? @json($compareTotalRevenue) : @json($totalRevenue),
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
