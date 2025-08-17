<?php

namespace App\DTO;

use App\Models\league_position;
use DateTime;

class leaguePositionDTO extends baseDTO
{
    /**
     * Create a new class instance.
     */
    private int $id;
    private int $team_id;
    private int $league_id;
    private int $season_id;
    private int $team_position;
    private int $played_matches;
    private int $wins;
    private int $losses;
    private int $draws;
    private int $goal_given;
    private int $goal_achieved;
    private int $points;
    private int $home_win;
    private int $away_win;
    private string $streak_type;
    private int $streak_count;
    private int $next_match_id;
    private DateTime $last_updated;

    public function __construct(
        int $id,
        int $team_id,
        int $league_id,
        int $season_id,
        int $team_position,
        int $played_matches,
        int $wins,
        int $losses,
        int $draws,
        int $goal_given,
        int $goal_achieved,
        int $points,
        ?int $home_win,
        ?int $away_win,
        ?string $streak_type,
        ?int $streak_count,
        ?int $next_match_id,
        ?DateTime $last_updated
    )
    {

        $this->id = $id;
        $this->team_id = $team_id;
        $this->league_id = $league_id;
        $this->season_id = $season_id;
        $this->team_position = $team_position;
        $this->played_matches = $played_matches;
        $this->wins = $wins;
        $this->losses = $losses;
        $this->draws = $draws;
        $this->goal_given = $goal_given;
        $this->goal_achieved = $goal_achieved;
        $this->points = $points;
        $this->home_win = $home_win;
        $this->away_win = $away_win;
        $this->streak_type = $streak_type;
        $this->streak_count = $streak_count;
        $this->next_match_id = $next_match_id;
        $this->last_updated = $last_updated;
    }

    public static function fromModel(league_position $model) : self
    {
        return new self(
            $model->id,
            $model->team_id,
            $model->league_id,
            $model->season_id,
            $model->team_position,
            $model->played_matches,
            $model->wins,
            $model->losses,
            $model->draws ,
            $model->goal_given,
            $model->goal_achieved,
            $model->points,
            $model->home_win,
            $model->away_win,
            $model->streak_type,
            $model->streak_count,
            $model->next_match_id,
            $model->last_updated
        );
    }


}
