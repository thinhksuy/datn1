<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Court extends Model
{
    use HasFactory;

    protected $table = 'courts';

    protected $primaryKey = 'Courts_ID';

    // Kích hoạt timestamps nhưng định nghĩa lại tên cột
    const CREATED_AT = 'Created_at';
    const UPDATED_AT = 'Updated_at';
    public $timestamps = true;

    // App\Models\Court.php
protected $fillable = [
    'Name',
    'Location',
    'Description',
    'Court_type',
    'Price_per_hour',
    'Status',
    'Image',
    'Created_at',
    'Updated_at',
];

    // Example relationship: nếu có bảng bookings
    public function bookings()
    {
        return $this->hasMany(CourtBooking::class, 'Courts_ID', 'Courts_ID');
    }

    // Scope: lọc sân đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('Status', true);
    }

    // Accessor: format giá
    public function getFormattedPriceAttribute()
    {
        return number_format($this->Price_per_hour, 0, ',', '.') . ' VND/giờ';
    }
}
