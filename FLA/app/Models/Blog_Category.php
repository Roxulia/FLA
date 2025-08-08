<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog_Category extends Model
{
    use HasFactory;

    protected $fillables = [
        'category_id',
        'blog_id'
    ];

    public function getBlogs()
    {
        return $this->belongsTo(Blogs::class,'blog_id');
    }

    public function getCategory()
    {
        return $this->belongsTo(Categories::class,'category_id');
    }
}
