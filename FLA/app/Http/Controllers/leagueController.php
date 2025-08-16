<?php

namespace App\Http\Controllers;

use App\Repository\leagueRepo;
use Illuminate\Http\Request;

class leagueController extends Controller
{
    private leagueRepo $league_repo;

    public function __construct(leagueRepo $league_repo)
    {
        $this->league_repo = $league_repo;
    }

    public function  getAllLeagues()
    {
         return response()->json($this->league_repo->getAllLeagues());
    }

    public function getLeagueByApiId(int $id)
    {
        $league = $this->league_repo->getLeagueByApiId($id);
        if (!$league) {
            return response()->json(['message' => 'League not found'], 404);
        }
        return response()->json($league);
    }
}
