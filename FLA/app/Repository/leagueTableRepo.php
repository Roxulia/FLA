<?php

namespace App\Repository;

use App\DTO\leaguePositionDTO;
use App\Models\league_position;

class leagueTableRepo
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(leaguePositionDTO $dto): leaguePositionDTO
    {
        $data = league_position::create(

                [
                    'team_id' => $dto->team_id,
                    'league_id' =>$dto->league_id,
                    'season_id' => $dto->season_id,
                    'team_position'=>$dto->team_position,
                    'played_matches'=>$dto->played_matches,
                    'wins'=>$dto->wins,
                    'losses'=>$dto->losses,
                    'draws'=>$dto->draws,
                    'goal_given' => $dto->goal_given,
                    'goal_achieved'=>$dto->goal_achieved,
                    'points' => $dto->points
                ]
            );

        return leaguePositionDTO::fromModel($data);
    }

    public function update(int $id, leaguePositionDTO $dto): ?leaguePositionDTO
    {
        $data = league_position::find($id);
        if (!$data) {
            return null;
        }

        $data->update(
                [
                    'team_id' => $dto->team_id,
                    'league_id' =>$dto->league_id,
                    'season_id' => $dto->season_id,
                    'team_position'=>$dto->team_position,
                    'played_matches'=>$dto->played_matches,
                    'wins'=>$dto->wins,
                    'losses'=>$dto->losses,
                    'draws'=>$dto->draws,
                    'goal_given' => $dto->goal_given,
                    'goal_achieved'=>$dto->goal_achieved,
                    'points' => $dto->points,
                    'home_win'=> $dto->home_win,
                    'away_win' => $dto->away_win,
                    'streak_type' => $dto->streak_type,
                    'streak_count' => $dto->streak_count,
                    'next_match_id' => $dto->next_match_id
                ]
            );

        return leaguePositionDTO::fromModel($data);
    }

    public function getById(int $id): ?leaguePositionDTO
    {
        $data = league_position::find($id);
        if (!$data) {
            return null;
        }

        return leaguePositionDTO::fromModel($data);
    }

    public function getAll() : array
    {
        return league_position::all()->map(function ($data) {
            return leaguePositionDTO::fromModel($data);
        })->toArray();
    }

    public function getTeamPosInLeague(int $team_id,int $league_id,int $season_id) : ?leaguePositionDTO
    {
        $data = league_position::where('team_id', $team_id)
                                ->where('league_id', $league_id)
                                ->where('season_id',$season_id)
                                ->first();
        if(!$data)
        {
            return null;
        }
        return leaguePositionDTO::fromModel($data);
    }

    public function getLeagueTable(int $league_id,int $season_id) : array
    {
        return league_position::where('league_id', $league_id)
                                ->where('season_id',$season_id)
                                ->map(function ($data) {
            return leaguePositionDTO::fromModel($data);
        })->toArray();
    }

}
