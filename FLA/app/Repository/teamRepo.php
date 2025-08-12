<?php

namespace App\Repository;

use App\Models\Teams;

class teamRepo
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAllTeams()
    {
        return Teams::all();
    }

    public function getAllApiId()
    {
        return Teams::pluck('id_from_api')->toArray();
    }

    public function getTeamByApiId(int $id)
    {
        return Teams::where('id_from_api','=',"{$id}")->get();
    }
}
