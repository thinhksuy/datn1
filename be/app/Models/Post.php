<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $table = 'posts';

    protected $primaryKey = 'Post_ID';

    public $timestamps = false;

    protected $fillable = [
        'User_ID',
        'Category_ID',
        'Title',
        'Thumbnail',
        'Content',
        'Excerpt',
        'Status',
        'View',
        'Created_at',
        'Updated_at',
    ];

    /**
     * Bài viết thuộc về một người dùng
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'User_ID', 'ID');
    }

    /**
     * Bài viết thuộc về một danh mục
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'Category_ID', 'Post_Categories_ID');
    }

    /**
     * Bài viết có thể có nhiều bình luận
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'Post_ID', 'Post_ID');
    }
}
