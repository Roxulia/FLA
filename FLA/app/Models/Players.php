<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Players extends Model
{
    use HasFactory;

    protected $primaryKey = 'player_id';

    protected $fillables = [
        'player_name',
        'player_position',
        'jersey_number',
        'id_from_api'
    ];

    protected $casts = [
        'jersey_number' => 'integer'
    ];

    public function matches()
    {
        return $this->belongsToMany(Matches::class, 'team_lineup', 'player_id', 'match_id')
                    ->using(Team_LineUp::class) // custom pivot model
                    ->withPivot([
                        'team_id',
                        'status',
                        'score',
                        'yellow_card',
                        'red_card',
                        'created_at',
                        'updated_at'
                    ]);
    }

    public function teams()
    {
        return $this->belongsToMany(Teams::class, 'team_lineup', 'player_id', 'team_id')
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
}
