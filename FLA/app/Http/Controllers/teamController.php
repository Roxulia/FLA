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
        return $this->team_repo->getAllTeams();
    }

    public function getTeamByApiId(int $id)
    {
        return $this->team_repo->getTeamByApiId($id);
    }
}
