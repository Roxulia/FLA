<?php

namespace App\Repository;

use App\DTO\playerDTO;
use App\Models\Players;
use Illuminate\Support\Facades\Cache;

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

        return Cache::remember($cachKey,now()->addMinute(5),function()use($data)
        {
            return playerDTO::fromModel($data);
        });
    }

    public function getAll(int $page = 1,int $per_page = 10)
    {
        $cachKey = "player_page_{$page}_per_page_{$per_page}";
        return Cache::remember($cachKey,now()->addMinute(5),function()use($per_page){
            $data = Players::paginate($per_page);
            $data -> getCollection()->transform(function($item){
                return playerDTO::fromModel($item);
            });
            return $data;
        });

    }

    public function getAllApiId() : array
    {
        return Players::pluck('id_from_api')->toArray();
    }

    public function getByApiId(int $id) : ?playerDTO
    {
        $cachKey = "player_id_from_api_{$id}";
        $data =  Players::where('id_from_api','=',"{$id}") -> get();

        if(!$data)
        {
            return null;
        }
        return Cache::remember($cachKey,now()->addMinute(5),function()use($data){
            return playerDTO::fromModel($data);
        });
    }

    public function delete(int $id)
    {
        $data = Players::find($id);
        if(!$data)
        {
            return false;
        }
        return $data->delete();
    }
}
