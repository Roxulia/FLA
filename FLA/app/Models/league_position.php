<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class league_position extends Model
{
    use HasFactory;

     protected $primaryKey = 'id';

    protected $fillable = [
        'team_id',
        'league_id',
        'team_position',
        'played_matches',
        'wins',
        'losses',
        'draws',
        'goal_given',
        'goal_achieved',
        'points',
        'home_wins',
        'away_wins',
        'streak_type',
        'streak_count',
        'next_match_id',
        'last_updated',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function team()
    {
        return $this->belongsTo(Teams::class, 'team_id');
    }

    public function league()
    {
        return $this->belongsTo(Leagues::class, 'league_id');
    }

    public function nextMatch()
    {
        return $this->belongsTo(Matches::class, 'next_match_id');
    }
}
