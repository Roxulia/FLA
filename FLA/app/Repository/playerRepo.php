<?php

namespace App\Repository;

use App\DTO\playerDTO;
use App\Models\Players;

class playerRepo
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(playerDTO $dto): playerDTO
    {
        $data = Players::create(

                [
                    'player_name' => $dto -> player_name,
                    'player_position' => $dto->player_position,
                    'jersey_number' => $dto->jersey_number,
                    'id_from_api'=> $dto -> id_from_api
                ]
            );

        return playerDTO::fromModel($data);
    }

    public function update(int $id, playerDTO $dto): ?playerDTO
    {
        $data = Players::find($id);
        if (!$data) {
            return null;
        }

        $data->update(
                [
                    'player_name' => $dto -> player_name,
                    'player_position' => $dto->player_position,
                    'jersey_number' => $dto->jersey_number,
                    'id_from_api'=> $dto -> id_from_api
                ]
            );

        return playerDTO::fromModel($data);
    }

    public function getById(int $id): ?playerDTO
    {
        $cachKey = "player_id_{$id}";
        $data = Players::find($id);
        if (!$data) {
            return null;
        }

        return playerDTO::fromModel($data);
    }

    public function getAll() : array
    {
        return Players::all()->map(function ($data) {
            return playerDTO::fromModel($data);
        })->toArray();
    }

    public function getAllApiId() : array
    {
        return Players::pluck('id_from_api')->toArray();
    }

    public function getByApiId(int $id) : ?playerDTO
    {
        $data =  Players::where('id_from_api','=',"{$id}") -> get();
        if(!$data)
        {
            return null;
        }
        return playerDTO::fromModel($data);
    }
}
