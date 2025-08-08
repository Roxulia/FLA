<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{
    use HasFactory;

    protected $primaryKey = 'blog_id';

    protected $fillables = [
        'title',
        'detail',
        'author',
        'created_at',
        'status',
        'thumbnail',
        'view_count'
    ];

    protected $casts = [
        'view_count' => 'integer'
    ];

    public function getCategory()
    {
        return $this->belongsToMany(Categories::class,'blog_category','blog_id','category_id')
                    ->using(Blog_Category::class);
    }
}
