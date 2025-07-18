<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourtBooking;

class CourtBookingApi extends Controller
{
    // GET /api/court-bookings
    public function index()
    {
        return response()->json(
            CourtBooking::with(['user', 'court', 'voucher'])->get(),
            200
        );
    }

    // POST /api/court-bookings
    public function store(Request $request)
    {
        $data = $request->validate([
            'User_ID'        => 'required|exists:user,ID',
            'Courts_ID'      => 'required|exists:courts,Courts_ID',
            'Booking_date'   => 'required|date',
            'Start_time'     => 'required|date_format:H:i:s',
            'End_time'       => 'required|date_format:H:i:s|after:Start_time',
            'Duration_hours' => 'required|integer|min:1',
            'Price_per_hour' => 'required|numeric|min:0',
            'Total_price'    => 'required|numeric|min:0',
            'Note'           => 'nullable|string',
            'Status'         => 'nullable|boolean',
            'Vouchers_ID'    => 'nullable|exists:vouchers,Vouchers_ID',
        ]);

        $data['Create_at'] = now();

        $booking = CourtBooking::create($data);
        $booking->load(['user', 'court', 'voucher']);

        return response()->json([
            'message' => 'Đặt sân thành công',
            'data'    => $booking
        ], 201);
    }

    // GET /api/court-bookings/{id}
    public function show($id)
    {
        $booking = CourtBooking::with(['user', 'court', 'voucher'])->find($id);

        if (!$booking) {
            return response()->json(['message' => 'Không tìm thấy'], 404);
        }

        return response()->json($booking);
    }

    // PUT /api/court-bookings/{id}
    public function update(Request $request, $id)
    {
        $booking = CourtBooking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Không tìm thấy'], 404);
        }

        $data = $request->validate([
            'Booking_date'   => 'sometimes|required|date',
            'Start_time'     => 'sometimes|required|date_format:H:i:s',
            'End_time'       => 'sometimes|required|date_format:H:i:s|after:Start_time',
            'Duration_hours' => 'sometimes|required|integer|min:1',
            'Price_per_hour' => 'sometimes|required|numeric|min:0',
            'Total_price'    => 'sometimes|required|numeric|min:0',
            'Note'           => 'nullable|string',
            'Status'         => 'nullable|boolean',
            'Vouchers_ID'    => 'nullable|exists:vouchers,Vouchers_ID',
        ]);

        $data['Update_at'] = now();

        $booking->update($data);
        $booking->load(['user', 'court', 'voucher']);

        return response()->json([
            'message' => 'Cập nhật thành công',
            'data'    => $booking
        ]);
    }

    // DELETE /api/court-bookings/{id}
    public function destroy($id)
    {
        $booking = CourtBooking::find($id);
        if (!$booking) {
            return response()->json(['message' => 'Không tìm thấy'], 404);
        }

        $booking->delete();

        return response()->json(['message' => 'Xóa đặt sân thành công'], 200);
    }
}
