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
            'api_id' => 'required|integer|unique:leagues,api_id',
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'logo' => 'nullable|url',
            'flag' => 'nullable|url',
            'season_start' => 'required|date',
            'season_end' => 'required|date|after_or_equal:season_start',
        ]);

        $league = $this->league_repo->create(leagueDTO::fromArray($request->all()));
        return response()->json($league, 201);
    }

    public function updateLeague(int $id, Request $request)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'country' => 'sometimes|required|string|max:255',
            'logo' => 'sometimes|nullable|url',
            'flag' => 'sometimes|nullable|url',
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
