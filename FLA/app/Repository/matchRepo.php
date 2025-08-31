<?php

namespace App\Repository;

use App\DTO\matchDTO;
use App\Enum\matchStatus;
use App\Models\Matches;
use Illuminate\Support\Facades\Cache;

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
                    'status' => matchStatus::from($dto -> status),
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
                    'status' => matchStatus::from($dto->status),
                    'league_id'=>$dto -> league_id,
                    'id_from_api'=>$dto -> id_from_api
                ]
            );

        return matchDTO::fromModel($match);
    }

    public function getById(int $id): ?matchDTO
    {
        $cacheKey = "match_{$id}";
        $match = Matches::find($id);
        if (!$match) {
            return null;
        }

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($match) {
            return matchDTO::fromModel($match);
        });
    }

    public function getAll(int $page = 1,int $per_page = 10)
    {
        $cacheKey = "matches_page_{$page}_perpage_{$per_page}";
        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($per_page) {
            $data = Matches::paginate($per_page);
            $data -> getCollection() -> transform(function($item){
                return matchDTO::fromModel($item);
            });
            return $data;
        });
    }

    public function getByName(string $name) : ?matchDTO
    {
        $cacheKey = "match_name_{$name}";
        $match =  Matches::where('name', 'LIKE', "%{$name}%")->get();
        if(!$match)
        {
            return null;
        }
        else
        {
           return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($match) {
                return matchDTO::fromModel($match);
            });
        }
    }

    public function getAllApiId() : array
    {
        return Matches::pluck('id_from_api')->toArray();
    }

    public function getByApiId(int $id) : ?matchDTO
    {
        $cacheKey = "match_api_id_{$id}";
        $match =  Matches::where('id_from_api','=',"{$id}") -> get();
        if(!$match)
        {
            return null;
        }
        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($match) {
            return matchDTO::fromModel($match);
        });
    }

    public function delete(int $id): bool
    {
        $match = Matches::find($id);
        if (!$match) {
            return false;
        }

        return $match->delete();
    }
}
