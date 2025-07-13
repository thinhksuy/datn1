<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'user_id',
        'order_code',
        'shipping_address',
        'note_user',
        'payment_method',
        'shiping_fee',
        'total_amount',
        'status',
        'status_method',
        'vourchers_id',
    ];

    // Một đơn hàng thuộc về một người dùng
      public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }



    // Nếu có bảng vouchers thì bạn có thể thêm quan hệ này:
    // public function voucher(): BelongsTo
    // {
    //     return $this->belongsTo(Voucher::class, 'vourchers_id', 'id');
    // }
}
