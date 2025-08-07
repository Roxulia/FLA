<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team_LineUp extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $fillable = [
        'team_id',
        'player_id',
        'match_id',
        'status',
        'score',
        'yellow_card',
        'red_card',
    ];

    protected $casts = [
        'score' => 'integer',
        'yellow_card' => 'integer',
        'red_card' => 'integer',
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

    public function player()
    {
        return $this->belongsTo(Players::class, 'player_id');
    }

    public function match()
    {
        return $this->belongsTo(Matches::class, 'match_id');
    }
}
