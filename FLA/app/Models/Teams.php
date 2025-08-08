<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teams extends Model
{
    use HasFactory;

    protected $primaryKey = 'team_id';

    // Optional if not using auto-incrementing ID or non-integer key
    // public $incrementing = true;
    // protected $keyType = 'int';

    protected $fillable = [
        'team_fullname',
        'team_shortform',
        'team_code',
        'country',
        'city',
        'stadium_name',
        'found_year',
        'logo',
        'is_national',
        'is_active',
    ];

    protected $cast = [
        'found_year' => 'date',
        'is_national' => 'bool',
        'is_active' => 'bool',
    ];

    public function homeMatches()
    {
        return $this->hasMany(Matches::class, 'home_team_id');
    }

    public function awayMatches()
    {
        return $this->hasMany(Matches::class, 'away_team_id');
    }

    public function players()
    {
        return $this->belongsToMany(Players::class, 'team_lineup', 'player_id', 'team_id')
                    ->using(Team_LineUp::class) // custom pivot model
                    ->withPivot([
                        'match_id',
                        'status',
                        'score',
                        'yellow_card',
                        'red_card',
                        'created_at',
                        'updated_at'
                    ]);
    }

    public function playedLeagues()
    {
        return $this->belongsToMany(Leagues::class, 'league_position', 'league_id', 'team_id')
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
