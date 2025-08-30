<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveData extends Model
{
    use HasFactory;
    protected $fillables = [
        'live_id',
        'home_name',
        'home_logo',
        'away_name',
        'away_logo',
        'home_score',
        'away_score',
        'video_link',
    ];
}
