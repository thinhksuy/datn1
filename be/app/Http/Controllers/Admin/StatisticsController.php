<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatisticsController extends Controller
{

    public function indexrevenue()
{
    $currentYear = now()->year;

    // Lấy dữ liệu đơn hàng theo tháng
    $orderStats = DB::table('orders')
        ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as revenue')
        ->whereYear('created_at', $currentYear)
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->get();

    // Lấy dữ liệu đặt sân theo tháng
    $bookingStats = DB::table('court_booking')
        ->selectRaw('MONTH(Booking_date) as month, SUM(Total_price) as revenue')
        ->whereYear('Booking_date', $currentYear)
        ->groupBy(DB::raw('MONTH(Booking_date)'))
        ->get();

    // Khởi tạo mảng 12 tháng
    $labels = [];
    $orderRevenue = [];
    $bookingRevenue = [];
    $totalRevenue = [];

    for ($i = 1; $i <= 12; $i++) {
        $labels[] = "Tháng $i";
        $orderRevenue[$i] = 0;
        $bookingRevenue[$i] = 0;
        $totalRevenue[$i] = 0;
    }

    foreach ($orderStats as $stat) {
        $orderRevenue[$stat->month] = $stat->revenue;
    }

    foreach ($bookingStats as $stat) {
        $bookingRevenue[$stat->month] = $stat->revenue;
    }

    // Cộng 2 nguồn lại theo từng tháng
    for ($i = 1; $i <= 12; $i++) {
        $totalRevenue[$i] = $orderRevenue[$i] + $bookingRevenue[$i];
    }

    return view('admin.statistics.revenue', [
        'labels' => $labels,
        'orderRevenue' => array_values($orderRevenue),
        'bookingRevenue' => array_values($bookingRevenue),
        'totalRevenue' => array_values($totalRevenue),
    ]);
}

    public function indexorder()
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        // Dữ liệu thống kê theo tháng
        $monthlyStats = DB::table('orders')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders, SUM(total_amount) as total_revenue')
            ->whereYear('created_at', $currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Gán giá trị mặc định 0 cho 12 tháng
        $labels = [];
        $totalAmount = [];
        $orderCounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = "Tháng $i";
            $totalAmount[$i] = 0;
            $orderCounts[$i] = 0;
        }

        foreach ($monthlyStats as $stat) {
            $totalAmount[$stat->month] = $stat->total_revenue;
            $orderCounts[$stat->month] = $stat->total_orders;
        }

        // Thống kê nhanh
        $orderThisMonth = DB::table('orders')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        $revenueThisMonth = DB::table('orders')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('total_amount');



        $newCourtBookings = 0; // Giả sử bạn chưa có bảng đặt sân (bạn có thể cập nhật sau)

        return view('admin.statistics.order', [
            'labels' => $labels,
            'totalAmount' => array_values($totalAmount),
            'orderCounts' => array_values($orderCounts),
            'orderThisMonth' => $orderThisMonth,
            'revenueThisMonth' => $revenueThisMonth,
            'newCourtBookings' => $newCourtBookings,
        ]);
    }



    public function indexbooking()
{
    $currentYear = now()->year;
    $currentMonth = now()->month;



    // Thống kê lịch đặt sân
    $monthlyCourtStats = DB::table('court_booking')
        ->selectRaw('MONTH(Booking_date) as month, COUNT(*) as total_bookings, SUM(Total_price) as total_booking_revenue')
        ->whereYear('Booking_date', $currentYear)
        ->groupBy(DB::raw('MONTH(Booking_date)'))
        ->get();

    // Mảng mặc định 12 tháng
    $labels = [];
    $totalAmount = [];
    $orderCounts = [];
    $courtBookingCounts = [];
    $courtBookingRevenue = [];

    for ($i = 1; $i <= 12; $i++) {
        $labels[] = "Tháng $i";
        $totalAmount[$i] = 0;
        $orderCounts[$i] = 0;
        $courtBookingCounts[$i] = 0;
        $courtBookingRevenue[$i] = 0;
    }



    foreach ($monthlyCourtStats as $stat) {
        $courtBookingCounts[$stat->month] = $stat->total_bookings;
        $courtBookingRevenue[$stat->month] = $stat->total_booking_revenue;
    }

    // Tổng trong tháng hiện tại
    $newCourtBookings = DB::table('court_booking')
        ->whereYear('Booking_date', $currentYear)
        ->whereMonth('Booking_date', $currentMonth)
        ->count();

    return view('admin.statistics.booking', [
        'labels' => $labels,
        'totalAmount' => array_values($totalAmount),
        'orderCounts' => array_values($orderCounts),
        'courtBookingCounts' => array_values($courtBookingCounts),
        'courtBookingRevenue' => array_values($courtBookingRevenue),
        'newCourtBookings' => $newCourtBookings,
    ]);
}

public function indexproduct()
{
    $stats = DB::table('order_details')
    ->join('products', 'order_details.Product_ID', '=', 'products.Product_ID')
    ->select(
        'products.Product_ID',
        'products.Name as product_name',
        DB::raw('SUM(order_details.quantity) as total_sold'),
        DB::raw('SUM(order_details.total) as total_revenue')
    )
    ->groupBy('products.Product_ID', 'products.Name')
    ->orderByDesc('total_sold')
    ->get();


    $productNames = $stats->pluck('product_name');
$productSales = $stats->pluck('total_sold');
$productRevenue = $stats->pluck('total_revenue');

return view('admin.statistics.product', compact('stats', 'productNames', 'productSales', 'productRevenue'));

}



}
