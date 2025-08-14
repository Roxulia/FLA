<?php

namespace App\Http\Controllers;

use App\Repository\matchRepo;
use Illuminate\Http\Request;

class matchController extends Controller
{
    private matchRepo $match_repo;

    public function __construct(matchRepo $repo)
    {
        $this->match_repo = $repo;
    }

    public function  getAllMatches()
    {
        return $this->match_repo->getAllMatches();
    }

    public function getMatchByApiId(int $id)
    {
        return $this->match_repo->getMatchByApiId($id);
    }
}
