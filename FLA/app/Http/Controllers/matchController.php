<?php

namespace App\Http\Controllers;

use App\Repository\matchRepo;
use Illuminate\Http\Request;
use App\DTO\matchDTO;

class matchController extends Controller
{
    private matchRepo $match_repo;

    public function __construct(matchRepo $repo)
    {
        $this->match_repo = $repo;
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
                    'league_id' => 'required|integer',
                    'id_from_api' => 'required|integer'
                ]);
            }
            catch (\Illuminate\Validation\ValidationException $e)
            {
                return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
            }
            $existingMatch = $this->match_repo->getByApiId($request->input('id_from_api'));
            if ($existingMatch) {
                return response()->json(['message' => 'Match with the same API ID already exists'], 409);
            }
            $match = $this->match_repo->create(matchDTO::fromArray($request->all()));
            return response()->json(['message' => 'Match created successfully', 'data' => $match], 201);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
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
                return response()->json(['message' => 'League not found'], 404);
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
}
