<?php

namespace App\Http\Controllers;

use App\Repository\matchRepo;
use Illuminate\Http\Request;
use App\DTO\matchDTO;
use App\Enum\matchStatus;
use App\Repository\leagueRepo;
use App\Repository\teamRepo;

class matchController extends Controller
{
    private matchRepo $match_repo;
    private teamRepo $team_repo;
    private leagueRepo $league_repo;

    public function __construct(matchRepo $repo,teamRepo $team_repo,leagueRepo $league_repo)
    {
        $this->match_repo = $repo;
        $this->team_repo = $team_repo;
        $this->league_repo = $league_repo;
    }

    public function createMatch(Request $request)
    {
        try
        {
            try
            {
                $request->validate([
                    'home_team_id' => 'required|integer',
                    'away_team_id' => 'required|integer',
                    'date' => 'required|date',
                    'time' => 'required',
                    'score' => 'nullable|string',
                    'status' => 'nullable|string',
                    'league_id' => 'required|integer',
                    'id_from_api' => 'required|integer'
                ]);
            }
            catch (\Illuminate\Validation\ValidationException $e)
            {
                return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
            }
            $league = $this->league_repo->getById($request->input('league_id'));
            if($league == null)
            {
                return response()->json(['message'=>'League not found'],404);
            }
            $existingMatch = $this->match_repo->getByApiId($request->input('id_from_api'));
            if ($existingMatch != null) {
                return response()->json(['message' => 'Match with the same API ID already exists'], 409);
            }
            $check = $this->checkTeamsExist($request->input('home_team_id'),$request->input('away_team_id'));
            if($check['status'] == false)
            {
                return response()->json(['message'=>$check['message']],404);
            }
            $match = $this->match_repo->create(matchDTO::fromArray($request->all()));
            return response()->json(['message' => 'Match created successfully', 'data' => $match], 201);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateMatch(int $id,Request $request)
    {
        try
        {
            try
            {
                $request->validate([
                    'home_team_id' => 'sometimes|required|integer',
                    'away_team_id' => 'sometimes|required|integer',
                    'date' => 'sometimes|required|date',
                    'time' => 'sometimes|required',
                    'score' => 'sometimes|nullable|string',
                    'status' => 'sometimes|nullable|string',
                    'league_id' => 'sometimes|required|integer',
                    'id_from_api' => 'sometimes|required|integer'
                ]);
            }
            catch (\Illuminate\Validation\ValidationException $e)
            {
                return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
            }
            $league = $this->league_repo->getById($request->input('league_id'));
            if($league == null)
            {
                return response()->json(['message'=>'League not found'],404);
            }
            $existingMatchByID = $this->match_repo->getById($id);
            $existingMatchByAPI = $this->match_repo->getByApiId($request->input('id_from_api'));
            if($existingMatchByAPI->id != $existingMatchByID->id)
            {
                return response()->json(['message'=> 'Match with same API ID existed'],409);
            }
            if ($existingMatchByID == null) {
                return response()->json(['message' => 'Match not Found'], 404);
            }
            $check = $this->checkTeamsExist($request->input('home_team_id'),$request->input('away_team_id'));
            if($check['status'] == false)
            {
                return response()->json(['message'=>$check['message']],404);
            }
            $match = $this->match_repo->update($id,matchDTO::fromArray($request->all()));
            return response()->json(['message' => 'Match updated successfully', 'data' => $match], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function getMatchStatusValue()
    {
        try
        {
            $data = array_column(matchStatus::cases(),'value');
            return response()->json(['message'=>"Match Status fetch successfully",'data'=>$data],200);
        }
        catch(\Exception $e)
        {
            return response()->json(['message'=>"An error occurred",'error'=>$e->getMessage()],500);
        }
    }

    public function deleteMatch(int $id)
    {
        try
        {
            $match = $this->match_repo->getById($id);
            if (!$match) {
                return response()->json(['message' => 'Match not found'], 404);
            }
            if( !$this->match_repo->delete($id))
            {
                return response()->json(['message' => 'Failed to delete match'], 500);
            }
            return response()->json(['message' => 'Match deleted successfully'], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function  getAllMatches(Request $request)
    {
        $page = $request->get('page', 1);
        $per_page = $request->get('per_page', 10);
        try{
            $page = (int)$page;
            $per_page = (int)$per_page;
            if ($page < 1 || $per_page < 1) {
                return response()->json(['message' => 'Page and per_page must be positive integers'], 400);
            }
            $data = $this->match_repo->getAll($page, $per_page);
            if (!$data) {
                return response()->json(['message' => 'No matches found'], 204);
            }
            return response()->json(['message' => 'Matches fetched successfully', 'data' => $data], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function getMatchById(int $id)
    {
        try{
            $data = $this->match_repo->getById($id);
            if (!$data) {
                return response()->json(['message' => 'Player not found'], 404);
            }
            return response()->json($data);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function getMatchByApiId(int $id)
    {
        try
        {
            $data = $this->match_repo->getByApiId($id);
            if (!$data) {
                return response()->json(['message' => 'League not found'], 404);
            }
            return response()->json($data);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    private function checkTeamsExist(int $home_id,int $away_id)
    {
        $home_team = $this->team_repo->getById($home_id);
        $away_team = $this->team_repo->getById($away_id);
        if($home_team == null)
        {
            return ['message'=> 'Home Team not Found',"status" => false];
        }
        if($away_team == null)
        {
            return ['message'=>'Away Team Not Found',"status" => false];
        }
        return ["status"=>true];
    }
}
