<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leagues extends Model
{
    use HasFactory;

    protected $primaryKey = 'league_id';

    // Optional if not using auto-incrementing ID or non-integer key
    // public $incrementing = true;
    // protected $keyType = 'int';

    protected $fillable = [
        'fullname',
        'shortform',
        'code',
        'country',
        'type',
        'tier',
        'season_start',
        'season_end',
        'current_season',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'season_start' => 'date',
        'season_end' => 'date',
    ];

    public function getMatches()
    {
        return $this->hasMany(Matches::class, 'league_id');
    }
}
