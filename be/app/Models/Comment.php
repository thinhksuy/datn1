<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $table = 'comments';

    protected $primaryKey = 'Comment_ID';

    public $timestamps = false;

    protected $fillable = [
        'Post_ID',
        'User_ID',
        'Content',
        'Status',
        'Create_at',
        'Update_at',
    ];

    /**
     * Mỗi bình luận thuộc về 1 bài viết
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'Post_ID', 'Post_ID');
    }

    /**
     * Mỗi bình luận thuộc về 1 người dùng
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'User_ID', 'ID');
    }
}
