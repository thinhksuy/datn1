<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    protected $table = 'order_details';
    protected $primaryKey = 'order_detail_id';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'Product_ID',
        'product_name',
        'SKU',
        'price',
        'quantity',
        'total',
        'create_at', // nếu bạn chưa đổi được
    ];

    protected $casts = [
        'price'      => 'float',
        'total'      => 'float',
        'quantity'   => 'integer',
        'create_at'  => 'datetime',
    ];

    // ✅ Quan hệ: Chi tiết đơn hàng thuộc về 1 đơn hàng
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    // ✅ Quan hệ: Chi tiết đơn hàng thuộc về 1 sản phẩm
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    }
}
