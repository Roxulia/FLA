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

    public function createLeague(Request $request)
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
            'api_id' => 'required|integer|unique:leagues,api_id',
        ]);

        $league = $this->league_repo->create(leagueDTO::fromArray($request->all()));
        return response()->json($league, 201);
    }

    public function updateLeague(int $id, Request $request)
    {
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

        $league = $this->league_repo->getLeagueByApiId($id);
        if (!$league) {
            return response()->json(['message' => 'League not found'], 404);
        }

        $updatedLeague = $this->league_repo->update($id, leagueDTO::fromArray($request->all()));
        return response()->json($updatedLeague);
    }

    public function deleteLeague(int $id)
    {
        $league = $this->league_repo->getLeagueByApiId($id);
        if (!$league) {
            return response()->json(['message' => 'League not found'], 404);
        }

        if ($this->league_repo->delete($id)) {
            return response()->json(['message' => 'League deleted successfully']);
        } else {
            return response()->json(['message' => 'Failed to delete league'], 500);
        }
    }
}
