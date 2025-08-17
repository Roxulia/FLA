<?php

namespace App\Http\Controllers;

use App\DTO\leagueDTO;
use App\Repository\leagueTableRepo;
use Illuminate\Http\Request;

class leaguePositionController extends Controller
{
    private leagueTableRepo $leagueTableRepo;

    public function __construct()
    {
        $this->leagueTableRepo = new leagueTableRepo();
    }

    public function getLeagueTable(int $league_id,int $season_id)
    {
        return response()->json($this->leagueTableRepo->getLeagueTable($league_id,$season_id));
    }

    public function getTeamPosInLeague(int $team_id,int $league_id,int $season_id)
    {
        return response()->json($this->leagueTableRepo->getTeamPosInLeague($team_id,$league_id,$season_id));
    }
}
