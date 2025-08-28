<?php

namespace App\Repository;

use App\DTO\leagueDTO;
use App\Models\Leagues;
use Illuminate\Support\Facades\Cache;



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
        $cacheKey = "league_{$id}";
        $league = Leagues::find($id);
        if (!$league) {
            return null;
        }

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($league) {
            return leagueDTO::fromModel($league);
        });
    }
    public function getAllLeagues(int $page = 1,int $per_page = 10)
    {
        $cacheKey = "leagues_page_{$page}_perpage_{$per_page}";

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($per_page) {
            $data = Leagues::paginate($per_page);
            $data -> getCollection() -> transform(function($item){
                return leagueDTO::fromModel($item);
            });
            return $data;
        });
    }

    public function getLeaguesByName(string $name) : ?leagueDTO
    {
        $cacheKey = "league_name_{$name}";
        $league =  Leagues::where('name', 'LIKE', "%{$name}%")->get();
        if(!$league)
        {
            return null;
        }
        else
        {
           return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($league) {
                return leagueDTO::fromModel($league);
            });
        }
    }

    public function getAllAPILeagueID() : array
    {
        return Leagues::pluck('id_from_api')->toArray();
    }

    public function getLeagueByApiId(int $id) : ?leagueDTO
    {
        $cacheKey = "league_api_id_{$id}";
        $league =  Leagues::where('id_from_api','=',"{$id}") -> get();
        if(!$league)
        {
            return null;
        }
        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($league) {
            return leagueDTO::fromModel($league);
        });
    }

    public function delete(int $id): bool
    {
        $league = Leagues::find($id);
        if (!$league) {
            return false;
        }
        return $league->delete();
    }
}
