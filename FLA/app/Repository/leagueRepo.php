<?php

namespace App\Repository;

use App\Models\Leagues;

class leagueRepo
{
    /**
     * Create a new class instance.
     */
    public function getLeagueById(int $id)
    {
        return Leagues::find($id); // SELECT * FROM leagues WHERE id = ? LIMIT 1
    }

    public function getAllLeagues()
    {
        return Leagues::all(); // SELECT * FROM leagues
    }

    public function getLeaguesByName(string $name)
    {
        return Leagues::where('name', 'LIKE', "%{$name}%")->get();
    }

    public function getAllAPILeagueID()
    {
        return Leagues::pluck('id_from_api')->toArray();
    }

    public function getLeagueByApiId(int $id)
    {
        return Leagues::where('id_from_api','=',"{$id}") -> get();
    }
}
