<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matches extends Model
{
    use HasFactory;

    // Table name is optional if it matches Laravel convention ("matches")
    // protected $table = 'matches';
    protected $primaryKey = 'match_id';
    protected $fillable = [
        'home_team_id',
        'away_team_id',
        'date',
        'time',
        'score',
        'status',
        'league_id',
        'id_from_api'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i:s', // Laravel handles time as datetime internally
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function homeTeam()
    {
        return $this->belongsTo(Teams::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Teams::class, 'away_team_id');
    }

    public function league()
    {
        return $this->belongsTo(Leagues::class, 'league_id');
    }
}
