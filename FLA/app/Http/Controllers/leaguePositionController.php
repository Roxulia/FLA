<?php

namespace App\Http\Controllers;

use App\DTO\leagueDTO;
use App\Repository\leagueTableRepo;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class leaguePositionController extends Controller
{
    private leagueTableRepo $leagueTableRepo;

    public function __construct()
    {
        $this->leagueTableRepo = new leagueTableRepo();
    }

    public function getAllTables(Request $request)
    {
        $page = $request->get('page', 1);
        $per_page = $request->get('per_page', 10);
        try{
            $page = (int)$page;
            $per_page = (int)$per_page;
            if ($page < 1 || $per_page < 1) {
                return response()->json(['message' => 'Page and per_page must be positive integers'], 400);
            }
            $data = $this->leagueTableRepo->getAll($page, $per_page);
            if (!$data) {
                return response()->json(['message' => 'No Tables found'], 204);
            }
            return response()->json(['message' => 'League Tables fetched successfully', 'data' => $data], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function getLeagueTableBySeason(int $league_id,int $season_id)
    {
        try
        {
            $data = $this->leagueTableRepo->getLeagueTable($league_id,$season_id);
            if($data.isEmpty())
            {
                return response()->json(['message'=>'League Table Not Found'],404);
            }
            return response()->json(['message'=>'League Table Fetch successfully','data'=>$data],200);
        }
        catch(\Exception $e)
        {
            return response()->json(['message'=>'An Error Occurred','error'=>$e->getMessage()],500);
        }
    }

    public function getTeamPosInLeague(int $team_id,int $league_id,int $season_id)
    {
        try
        {
            $data = $this->leagueTableRepo->getTeamPosInLeague($team_id,$league_id,$season_id);
            if($data == null)
            {
                return response()->json(['message'=>'Team Position Not Found'],404);

            }
            return response()->json(['message'=>'Team Position Fetch Successfully','data' => $data],200);
        }
        catch(\Exception $e)
        {
            return response()->json(['message'=>'An Error Occurred','error'=>$e->getMessage()],500);
        }
    }

    public function getAllByLeague(int $league_id)
    {
        try
        {
            $data = $this->leagueTableRepo->getAllByLeague($league_id);
            if($data.isEmpty())
            {
                return response()->json(['message'=>'League Table Not Found'],404);
            }
            return response()->json(['message'=>'League Table Fetch successfully','data'=>$data],200);
        }
        catch(\Exception $e)
        {
            return response()->json(['message'=>'An Error Occurred','error'=>$e->getMessage()],500);
        }
    }
}
