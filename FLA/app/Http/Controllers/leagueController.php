<?php

namespace App\Http\Controllers;

use App\DTO\leagueDTO;
use App\Repository\leagueRepo;
use Illuminate\Http\Request;

class leagueController extends Controller
{
    private leagueRepo $league_repo;

    public function __construct(leagueRepo $league_repo)
    {
        $this->league_repo = $league_repo;
    }

    public function  getAllLeagues(Request $request)
    {
        $page = $request->get('page', 1);
        $per_page = $request->get('per_page', 10);
        try{
            $page = (int)$page;
            $per_page = (int)$per_page;
            if ($page < 1 || $per_page < 1) {
                return response()->json(['message' => 'Page and per_page must be positive integers'], 400);
            }
            $data = $this->league_repo->getAllLeagues($page, $per_page);
            if (!$data) {
                return response()->json(['message' => 'No leagues found'], 204);
            }
            return response()->json(['message' => 'Leagues fetched successfully', 'data' => $data], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }

    }

    public function getLeagueByApiId(int $id)
    {
        try{
            $league = $this->league_repo->getLeagueByApiId($id);
            if (!$league) {
                return response()->json(['message' => 'League not found'], 204);
            }
            return response()->json(['message' => 'League fetched successfully', 'data' => $league], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function createLeague(Request $request)
    {
        try
        {
            $request->validate([

                'fullname' => 'required|string|max:255',
                'shortform' => 'nullable|string|max:100',
                'code' => 'nullable|string|max:50',
                'country' => 'required|string|max:255',
                'type' => 'nullable|string|max:100',
                'tier' => 'nullable|integer|min:1',
                'season_start' => 'nullable|date',
                'season_end' => 'nullable|date|after_or_equal:season_start',
                'current_season' => 'nullable|string|max:50',
                'logo' => 'nullable|url',
                'is_active' => 'boolean',
                'id_from_api' => 'required|integer|unique:leagues,id_from_api',
            ]);
        }catch (\Exception $e)
        {
            return response()->json(['message' => 'Validation error', 'error' => $e->getMessage()], 422);
        }
        $existingLeague = $this->league_repo->getLeagueByApiId($request->input('id_from_api'));
        if ($existingLeague) {
            return response()->json(['message' => 'League with this API ID already exists'], 409);
        }
        try{
            $league = $this->league_repo->create(leagueDTO::fromArray($request->all()));
            return response()->json(['message' => 'League Created Successfully','data' =>$league], 201);
        }
        catch(\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }

    }

    public function updateLeague(int $id, Request $request)
    {
        try{
            $request->validate([
                'fullname' => 'sometimes|required|string|max:255',
                'shortform' => 'sometimes|nullable|string|max:100',
                'code' => 'sometimes|nullable|string|max:50',
                'country' => 'sometimes|required|string|max:255',
                'type' => 'sometimes|nullable|string|max:100',
                'tier' => 'sometimes|nullable|integer|min:1',
                'current_season' => 'sometimes|nullable|string|max:50',
                'is_active' => 'sometimes|boolean',
                'logo' => 'sometimes|nullable|url',
                'season_start' => 'sometimes|required|date',
                'season_end' => 'sometimes|required|date|after_or_equal:season_start',
            ]);
        }catch (\Exception $e)
        {
            return response()->json(['message' => 'Validation error', 'error' => $e->getMessage()], 422);
        }

        $league = $this->league_repo->getById($id);
        if (!$league) {
            return response()->json(['message' => 'League not found'], 404);
        }
        try{
            $updatedLeague = $this->league_repo->update($id, leagueDTO::fromArray($request->all()));
            return response()->json(['message' => 'League updated successfully', 'data' => $updatedLeague], 200);
        }
        catch(\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }

    }

    public function deleteLeague(int $id)
    {
        $league = $this->league_repo->getLeagueByApiId($id);
        if (!$league) {
            return response()->json(['message' => 'League not found'], 404);
        }

        if ($this->league_repo->delete($id)) {
            return response()->json(['message' => 'League deleted successfully'],200);
        } else {
            return response()->json(['message' => 'Failed to delete league'], 500);
        }
    }
}
