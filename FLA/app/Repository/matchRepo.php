<?php

namespace App\Repository;

use App\Models\Matches;

class matchRepo
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAllMatches()
    {
        return Matches::all();
    }

    public function getAllApiId()
    {
        return Matches::pluck('id_from_api')->toArray();
    }

    public function getMatchByApiId(int $id)
    {
        return Matches::where('id_from_api','=',"{$id}")->get();
    }
}
