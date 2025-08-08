<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';
    protected $fillable = 'category_name';

    public function getBlogs()
    {
        return $this->belongsToMany(Blogs::class,'blog_category','category_id','blog_id')
                    ->using(Blog_Category::class);
    }
}
