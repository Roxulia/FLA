<?php

namespace App\Repository;

use App\DTO\liveDataDTO;
use App\Models\LiveData;

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
        $data = LiveData::find($id);
        if (!$data) {
            return null;
        }

        return liveDataDTO::fromModel($data);
    }

    public function getAll() : array
    {
        return LiveData::all()->map(function ($data) {
            return liveDataDTO::fromModel($data);
        })->toArray();
    }

}
