<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';

    protected $primaryKey = 'Vouchers_ID';

    public $timestamps = false;

    protected $fillable = [
        'Code',
        'Discount_type',
        'Discount_value',
        'Max_uses',
        'Expires',
        'applies_to',
        'Paid_at',
    ];

    // Nếu có quan hệ với bảng orders
    // public function orders()
    // {
    //     return $this->hasMany(Order::class, 'vouchers_id', 'Vouchers_ID');
    // }
}
