<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng
    public function index(Request $request)
{
    $query = Order::with('user');

    if ($request->filled('order_code')) {
        $query->where('order_code', 'like', '%' . $request->order_code . '%');
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    $orders = $query->orderByDesc('created_at')->paginate(10)->appends($request->query());

    return view('admin.orders.index', compact('orders'));
}


    // Xem chi tiết đơn hàng
  public function show($id)
{
    $order = Order::with(['user', 'details.product'])->findOrFail($id);
    return view('admin.orders.show', compact('order'));
}



    // Hiển thị form sửa đơn hàng
    public function edit($id)
    {
        $order = Order::findOrFail($id);

        return view('admin.orders.edit', compact('order'));
    }

    // Cập nhật trạng thái đơn hàng
   public function update(Request $request, $id)
{
    $validated = $request->validate([
        'shipping_address' => 'required|string|max:255',
        'note_user'        => 'nullable|string',
        'payment_method'   => 'required|string',
        'shiping_fee'      => 'nullable|numeric|min:0',
        'status'           => 'required|string',
        'status_method'    => 'nullable|string',
    ]);

    $order = Order::findOrFail($id);
    // ❌ Nếu đơn đã hủy, không cho cập nhật nữa
    if ($order->status === 'cancelled') {
        return redirect()->back()->with('error', 'Đơn hàng đã hủy và không thể cập nhật trạng thái.');
    }

    // ✅ Tiến hành cập nhật
    $order->status = $request->input('status');

    $order->shipping_address = $validated['shipping_address'];
    $order->note_user        = $validated['note_user'];
    $order->payment_method   = $validated['payment_method'];
    $order->shiping_fee      = $validated['shiping_fee'] ?? 0;
    $order->status           = $validated['status'];
    $order->status_method    = $validated['status_method'];
    $order->updated_at       = now();

    $order->save();

    return redirect()->route('admin.orders.index')->with('success', 'Cập nhật đơn hàng thành công!');
}


    public function statistics()
{
    $totalOrders = Order::count();
    $totalRevenue = Order::sum('total_amount');
    $todayOrders = Order::whereDate('created_at', now()->toDateString())->count();

    // ➕ Thêm dòng này:
    $completedOrders = Order::where('status', 'completed')->count();
    $cancelledOrders = Order::where('status', 'cancelled')->count();

    // Thống kê theo ngày gần đây (hoặc theo tháng nếu bạn muốn)
    $orders = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
        ->where('created_at', '>=', now()->subDays(6))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    $chartLabels = $orders->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'));
    $chartData = $orders->pluck('revenue');

    // ➕ Thêm truyền biến vào view:
    return view('admin.orders.statistics', compact(
        'totalOrders',
        'totalRevenue',
        'todayOrders',
        'completedOrders',    // ✅
        'cancelledOrders',    // ✅
        'chartLabels',
        'chartData'
    ));
}



public function destroy($id)
{
    $order = Order::findOrFail($id);
    $order->delete();

    return redirect()->route('admin.orders.index')->with('success', 'Đã xóa đơn hàng thành công.');
}


}
