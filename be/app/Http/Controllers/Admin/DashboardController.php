<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ✅ Tên các tháng
        $labels = [
            'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4',
            'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8',
            'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
        ];

        // ✅ Lấy thống kê đơn hàng theo tháng trong năm hiện tại
        $monthlyStats = DB::table('orders')
            ->selectRaw('MONTH(Created_at) as month, SUM(total_amount) as total, COUNT(*) as count')
            ->where('Status', 'completed')
            ->whereYear('Created_at', now()->year)
            ->groupByRaw('MONTH(Created_at)')
            ->orderByRaw('MONTH(Created_at)')
            ->get();

        $totalAmount = array_fill(0, 12, 0);
        $orderCounts = array_fill(0, 12, 0);

        foreach ($monthlyStats as $stat) {
            $i = $stat->month - 1;
            $totalAmount[$i] = (float) $stat->total;
            $orderCounts[$i] = (int) $stat->count;
        }

        // ✅ Thống kê tháng hiện tại
        $month = now()->month;
        $year = now()->year;

        $orderThisMonth = DB::table('orders')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();

        $revenueThisMonth = DB::table('orders')
            ->where('status', 'completed')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('total_amount');

        // ❗ Nếu chưa có bảng users hoặc court_bookings thì gán 0
        $newUsers = 0;
        $newCourtBookings = 0;

        return view('admin.index', compact(
            'labels',
            'totalAmount',
            'orderCounts',
            'orderThisMonth',
            'revenueThisMonth',
            'newUsers',
            'newCourtBookings'
        ));
    }
}
