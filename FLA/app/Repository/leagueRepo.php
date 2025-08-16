<?php

namespace App\Repository;

use App\DTO\leagueDTO;
use App\Models\Leagues;

class leagueRepo
{
    /**
     * Create a new class instance.
     */
    public function create(LeagueDTO $dto): LeagueDTO
    {
        $league = Leagues::create(

                [
                    'fullname' => $dto -> fullName,
                    'shortform'=> $dto ->shortForm,
                    'code'=> $dto -> code,
                    'country'=> $dto -> country,
                    'type'=> $dto -> type,
                    'tier'=> $dto -> tier,
                    'season_start'=> $dto -> season_start,
                    'season_end'=> $dto -> season_end,
                    'current_season'=> $dto -> current_season,
                    'logo'=> $dto -> logo,
                    'id_from_api'=> $dto -> id_from_api
                ]
            );

        return leagueDTO::fromModel($league);
    }

    public function update(int $id, LeagueDTO $dto): ?LeagueDTO
    {
        $league = Leagues::find($id);
        if (!$league) {
            return null;
        }

        $league->update(
                [
                    'fullname' => $dto -> fullName,
                    'shortform'=> $dto ->shortForm,
                    'code'=> $dto -> code,
                    'country'=> $dto -> country,
                    'type'=> $dto -> type,
                    'tier'=> $dto -> tier,
                    'season_start'=> $dto -> season_start,
                    'season_end'=> $dto -> season_end,
                    'current_season'=> $dto -> current_season,
                    'logo'=> $dto -> logo,
                    'id_from_api'=> $dto -> id_from_api
                ]
            );

        return leagueDTO::fromModel($league);
    }

    public function getById(int $id): ?leagueDTO
    {
        $league = Leagues::find($id);
        if (!$league) {
            return null;
        }

        return leagueDTO::fromModel($league);
    }
    public function getAllLeagues() : array
    {
        return Leagues::all()->map(function ($league) {
            return leagueDTO::fromModel($league);
        })->toArray();
    }

    public function getLeaguesByName(string $name) : ?leagueDTO
    {
        $league =  Leagues::where('name', 'LIKE', "%{$name}%")->get();
        if(!$league)
        {
            return null;
        }
        else
        {
           return leagueDTO::fromModel($league);
        }
    }

    public function getAllAPILeagueID() : array
    {
        return Leagues::pluck('id_from_api')->toArray();
    }

    public function getLeagueByApiId(int $id) : ?leagueDTO
    {
        $league =  Leagues::where('id_from_api','=',"{$id}") -> get();
        if(!$league)
        {
            return null;
        }
        return leagueDTO::fromModel($league);
    }
}
