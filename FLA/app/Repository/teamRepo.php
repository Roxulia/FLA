<?php

namespace App\Repository;

use App\DTO\teamDTO;
use App\Models\Teams;

class teamRepo
{
    /**
     * Create a new class instance.
     */
    public function create(teamDTO $dto): teamDTO
    {
        $team = Teams::create(

                [
                    'fullname' => $dto -> fullName,
                    'shortform'=> $dto ->shortForm,
                    'code'=> $dto -> code,
                    'country'=> $dto -> country,
                    'city'=> $dto -> city,
                    'stadium_name'=> $dto -> stadium_name,
                    'found_year'=> $dto -> found_year,
                    'logo'=> $dto -> logo,
                    'id_from_api'=> $dto -> id_from_api
                ]
            );

        return teamDTO::fromModel($team);
    }

    public function update(int $id, teamDTO $dto): ?teamDTO
    {
        $team = Teams::find($id);
        if (!$team) {
            return null;
        }

        $team->update(
                [
                    'fullname' => $dto -> fullName,
                    'shortform'=> $dto ->shortForm,
                    'code'=> $dto -> code,
                    'country'=> $dto -> country,
                    'city'=> $dto -> city,
                    'stadium_name'=> $dto -> stadium_name,
                    'found_year'=> $dto -> found_year,
                    'logo'=> $dto -> logo,
                    'is_national' => $dto -> is_national,
                    'is_active' => $dto -> is_active,
                    'id_from_api'=> $dto -> id_from_api
                ]
            );

        return teamDTO::fromModel($team);
    }

    public function getById(int $id): ?teamDTO
    {
        $league = Teams::find($id);
        if (!$league) {
            return null;
        }

        return teamDTO::fromModel($league);
    }

    public function getAll() : array
    {
        return Teams::all()->map(function ($league) {
            return teamDTO::fromModel($league);
        })->toArray();
    }

    public function getByName(string $name) : ?teamDTO
    {
        $league =  Teams::where('name', 'LIKE', "%{$name}%")->get();
        if(!$league)
        {
            return null;
        }
        else
        {
           return teamDTO::fromModel($league);
        }
    }

    public function getAllApiId() : array
    {
        return Teams::pluck('id_from_api')->toArray();
    }

    public function getByApiId(int $id) : ?teamDTO
    {
        $league =  Teams::where('id_from_api','=',"{$id}") -> get();
        if(!$league)
        {
            return null;
        }
        return teamDTO::fromModel($league);
    }
}
