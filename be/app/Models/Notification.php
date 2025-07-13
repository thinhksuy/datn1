<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $primaryKey = 'Notifications_ID';

    public $timestamps = false;

    protected $fillable = [
        'User_ID',
        'Title',
        'Message',
        'Type',
        'Created_at',
        'Updated_at',
    ];

    /**
     * Mỗi thông báo thuộc về 1 người dùng.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'User_ID', 'ID');
    }
}
