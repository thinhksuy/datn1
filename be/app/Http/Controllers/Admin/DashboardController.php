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

        // Thống kê khách hàng mới trong tháng hiện tại
    $newUsers = DB::table('user')
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->count();

    // Thống kê lịch đặt sân mới trong tháng hiện tại
    $newCourtBookings = DB::table('court_booking')
        ->whereMonth('create_at', $month)
        ->whereYear('create_at', $year)
        ->count();

    // Thống kê tổng số đơn hàng
    $totalOrders = DB::table('orders')->count();

    // Thống kê tổng số khách hàng
    $totalUsers = DB::table('user')->count();

    // Tổng số doanh thu
    $totalRevenue = DB::table('orders')
        ->where('status', 'completed')
        ->sum('total_amount');

    // Tổng số lượt đặt sân
    $totalCourtBookings = DB::table('court_booking')->count();

    // Đơn hàng gần đây (5 đơn mới nhất)
    $recentOrders = DB::table('orders')
        ->join('user', 'orders.user_id', '=', 'user.id')
        ->select('orders.*', 'user.name as user_name')
        ->orderByDesc('orders.created_at')
        ->limit(5)
        ->get();

    // Lịch đặt sân gần đây (5 lịch mới nhất)
    $recentBookings = DB::table('court_booking')
        ->join('user', 'court_booking.user_id', '=', 'user.id')
        ->select('court_booking.*', 'user.name as user_name')
        ->orderByDesc('court_booking.created_at')
        ->limit(5)
        ->get();


            return view('admin.index', compact(
            'labels',
            'totalAmount',
            'orderCounts',
            'orderThisMonth',
            'revenueThisMonth',
            'newUsers',
            'newCourtBookings',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'totalCourtBookings',
            'recentOrders',
            'recentBookings'
        ));

    }
}
