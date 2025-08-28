<?php

namespace App\DTO;

use App\Models\Matches;
use Illuminate\Support\Facades\Date;
use Ramsey\Uuid\Type\Time;

class matchDTO extends baseDTO
{
    /**
     * Create a new class instance.
     */
    private int $match_id;
    private int $home_team_id;
    private int $away_team_id;
    private string $date;
    private string $time;
    private string $score;
    private string $status;
    private int $league_id;
    private int $id_from_api;

    public function __construct(
        int $match_id,
        int $home_team_id,
        int $away_team_id,
        string $date,
        string $time,
        string $score,
        ?string $status,
        int $league_id,
        int $id_from_api
    )
    {
        $this->match_id = $match_id;
        $this->home_team_id = $home_team_id;
        $this->away_team_id = $away_team_id;
        $this->date = $date;
        $this->time = $time;
        $this->score = $score;
        $this->status = $status;
        $this->league_id = $league_id;
        $this->id_from_api = $id_from_api;
    }

    public static function fromModel(Matches $match) : self
    {
        return new self(
            $match->match_id,
            $match->home_team_id,
            $match->away_team_id,
            $match->date,
            $match->time,
            $match->score,
            $match->status,
            $match->league_id,
            $match->id_from_api
        );
    }

    public static function fromArray(array $data) : self
    {
        return new self(
            $data['match_id'] ?? 0,
            $data['home_team_id'],
            $data['away_team_id'],
            $data['date'],
            $data['time'],
            $data['score'] ?? '',
            $data['status'] ?? null,
            $data['league_id'],
            $data['id_from_api']
        );
    }

}
