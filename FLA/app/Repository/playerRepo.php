<?php

namespace App\Repository;

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

    public function getAllPlayers()
    {
        return Players::all();
    }

    public function getAllApiId()
    {
        return Players::pluck('id_from_api')->toArray();
    }

    public function getPlayerByApiId(int $id)
    {
        return Players::where('id_from_api','=',"{$id}")->get();
    }
}
