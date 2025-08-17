<?php

namespace App\Http\Controllers;

use App\Repository\teamRepo;
use Illuminate\Http\Request;

class teamController extends Controller
{
    private teamRepo $team_repo;

    public function __construct(teamRepo $repo)
    {
        $this->team_repo = $repo;
    }

    public function  getAllTeams()
    {
        return response()->json($this->team_repo->getAll());
    }

    public function getTeamByApiId(int $id)
    {
        $data = $this->team_repo->getByApiId($id);
        if (!$data) {
            return response()->json(['message' => 'League not found'], 404);
        }
        return response()->json($data);
    }
}
