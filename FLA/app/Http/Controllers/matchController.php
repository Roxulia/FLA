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
        return response()->json($this->match_repo->getAll());
    }

    public function getMatchByApiId(int $id)
    {
        $data = $this->match_repo->getByApiId($id);
        if (!$data) {
            return response()->json(['message' => 'League not found'], 404);
        }
        return response()->json($data);
    }
}
