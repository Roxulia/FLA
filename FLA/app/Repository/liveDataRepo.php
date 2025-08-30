<?php

namespace App\Repository;

use App\DTO\liveDataDTO;
use App\Events\MatchUpdateEvent;
use App\Events\MatchfinishedEvent;
use App\Models\LiveData;
use Illuminate\Support\Facades\Cache;

class liveDataRepo
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(liveDataDTO $dto) : liveDataDTO
    {
        $data = LiveData::create(

                [
                    'live_id' => $dto->live_id,
                    'home_name' => $dto -> home_name,
                    'home_logo' => $dto->home_logo,
                    'away_name' => $dto->away_name,
                    'away_logo'=> $dto ->away_logo,
                    'home_score' => $dto->home_score,
                    'away_score' => $dto->away_score
                ]
            );

        return liveDataDTO::fromModel($data);
    }

    public function store(liveDataDTO $dto): liveDataDTO
    {

        $data = LiveData::updateOrCreate(
                [
                    'live_id' => $dto->live_id,
                    'home_name' => $dto -> home_name,
                    'home_logo' => $dto->home_logo,
                    'away_name' => $dto->away_name,
                    'away_logo'=> $dto ->away_logo,
                    'home_score' => $dto->home_score,
                    'away_score' => $dto->away_score
                ]
            );
        broadcast(new MatchUpdateEvent(liveDataDTO::fromModel($data)));
        return liveDataDTO::fromModel($data);
    }

    public function update(int $id, liveDataDTO $dto): ?liveDataDTO
    {
        $data = LiveData::find($id);
        if (!$data) {
            return null;
        }

        $data->update(
                [
                    'live_id' => $dto->live_id,
                    'home_name' => $dto -> home_name,
                    'home_logo' => $dto->home_logo,
                    'away_name' => $dto->away_name,
                    'away_logo'=> $dto ->away_logo,
                    'home_score' => $dto->home_score,
                    'away_score' => $dto->away_score
                ]
            );

        return liveDataDTO::fromModel($data);
    }

    public function getById(int $id): ?liveDataDTO
    {
        $cacheKey = "live_id_{$id}";
        $data = LiveData::find($id);
        if (!$data) {
            return null;
        }

        return Cache::remember($cacheKey,now()->addMinute(5),function()use($data){
            return liveDataDTO::fromModel($data);
        });
    }

    public function getAllUsingPagination(int $page,int $per_page) : array
    {
        $cacheKey = "live_page_{$page}_per_page_{$per_page}";

        return Cache::remember($cacheKey,now()->addMinute(5),function() use($per_page)
        {
            $data = LiveData::paginate($per_page);
            $data->getCollection()->transform(function ($item)
            {
                return liveDataDTO::fromModel($item);
            });
            return $data;
        });
    }

    public function getAll() : array
    {
        $cacheKey = "live_all";
        $data = LiveData::all();
        return Cache::remember($cacheKey,now()->addMinute(5),function() use($data)
        {
            $data->getCollection()->transform(function ($item)
            {
                return liveDataDTO::fromModel($item);
            });
            return $data;
        });
    }

    public function delete(int $id): bool
    {
        $data = LiveData::find($id);
        if (!$data) {
            return false;
        }

        $res = $data->delete();
        if ($res) {
            broadcast(new MatchfinishedEvent(liveDataDTO::fromModel($data))); // Broadcast null to indicate deletion
        }
        return $res;
    }

}
