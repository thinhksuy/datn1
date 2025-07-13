<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $table = 'post_categories';

    protected $primaryKey = 'Post_Categories_ID';

    public $timestamps = false;

    protected $fillable = [
        'Title',
        'Content',
        'Status',
        'View',
        'Created_at',
        'Updated_at',
    ];

    // Nếu bạn có quan hệ với bảng posts thì có thể khai báo:
    // public function posts()
    // {
    //     return $this->hasMany(Post::class, 'Post_Categories_ID', 'Post_Categories_ID');
    // }
}
