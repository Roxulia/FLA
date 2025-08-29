<?php

namespace App\Repository;

use App\DTO\teamDTO;
use App\Models\Teams;
use Illuminate\Support\Facades\Cache;

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
        $cacheKey = "team_id_{$id}";
        $team = Teams::find($id);
        if (!$team) {
            return null;
        }

        return Cache::remember($cacheKey,now()->addMinute(5), function () use($team){
            return teamDTO::fromModel($team);
        });
    }

    public function getAll(int $page,int $per_page)
    {
        $cacheKey = "teams_page_{$page}_perpage_{$per_page}";
        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($per_page) {
            $data = Teams::paginate($per_page);
            $data -> getCollection() -> transform(function($item){
                return teamDTO::fromModel($item);
            });
            return $data;
        });
    }

    public function getByName(string $name) : ?teamDTO
    {
        $cacheKey = "team_name_{$name}";
        $team =  Teams::where('name', 'LIKE', "%{$name}%")->get();
        if(!$team)
        {
            return null;
        }
        else
        {
           return Cache::remember($cacheKey,now()->addMinute(5),function() use($team){
            return teamDTO::fromModel($team);
           });
        }
    }

    public function getAllApiId() : array
    {
        return Teams::pluck('id_from_api')->toArray();
    }

    public function getByApiId(int $id) : ?teamDTO
    {
        $cacheKey = "team_id_from_api_{$id}";
        $team =  Teams::where('id_from_api','=',"{$id}") -> get();
        if(!$team)
        {
            return null;
        }
        return Cache::remember($cacheKey,now()->addMinute(5),function () use ($team){
            return teamDTO::fromModel($team);
        });
    }

    public function delete(int $id) : bool
    {
        $team = Teams::find($id);
        if(!$team)
        {
            return false;
        }
        return $team->delete();
    }
}
