<?php

namespace App\Repository;

use App\DTO\matchDTO;
use App\Models\Matches;

class matchRepo
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(matchDTO $dto): matchDTO
    {
        $match = Matches::create(

                [
                    'home_team_id' => $dto -> home_team_id,
                    'away_team_id' => $dto -> away_team_id,
                    'date' => $dto -> date,
                    'time' => $dto -> time,
                    'score' => $dto -> score,
                    'league_id'=>$dto -> league_id,
                    'id_from_api'=>$dto -> id_from_api
                ]
            );

        return matchDTO::fromModel($match);
    }

    public function update(int $id, matchDTO $dto): ?matchDTO
    {
        $match = Matches::find($id);
        if (!$match) {
            return null;
        }

        $match->update(
                [
                    'home_team_id' => $dto -> home_team_id,
                    'away_team_id' => $dto -> away_team_id,
                    'date' => $dto -> date,
                    'time' => $dto -> time,
                    'score' => $dto -> score,
                    'league_id'=>$dto -> league_id,
                    'id_from_api'=>$dto -> id_from_api
                ]
            );

        return matchDTO::fromModel($match);
    }

    public function getById(int $id): ?matchDTO
    {
        $match = Matches::find($id);
        if (!$match) {
            return null;
        }

        return matchDTO::fromModel($match);
    }

    public function getAll() : array
    {
        return Matches::all()->map(function ($match) {
            return matchDTO::fromModel($match);
        })->toArray();
    }

    public function getByName(string $name) : ?matchDTO
    {
        $match =  Matches::where('name', 'LIKE', "%{$name}%")->get();
        if(!$match)
        {
            return null;
        }
        else
        {
           return matchDTO::fromModel($match);
        }
    }

    public function getAllApiId() : array
    {
        return Matches::pluck('id_from_api')->toArray();
    }

    public function getByApiId(int $id) : ?matchDTO
    {
        $match =  Matches::where('id_from_api','=',"{$id}") -> get();
        if(!$match)
        {
            return null;
        }
        return matchDTO::fromModel($match);
    }
}
