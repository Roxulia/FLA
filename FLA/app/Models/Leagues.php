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
        'id_from_api'
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

    public function getTeams()
    {
        return $this->belongsToMany(Teams::class, 'league_position', 'league_id', 'team_id')
                    ->using(league_position::class) // custom pivot model
                    ->withPivot([
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
                    ]);
    }
}
