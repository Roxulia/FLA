<?php

namespace App\Http\Controllers;

use App\Repository\playerRepo;
use Illuminate\Http\Request;

class playerController extends Controller
{
    private playerRepo $player_repo;

    public function __construct(playerRepo $repo)
    {
        $this->player_repo = $repo;
    }

    public function  getAllPlayers()
    {
        return response()->json($this->player_repo->getAll());
    }

    public function getPlayerByApiId(int $id)
    {
        $data = $this->player_repo->getByApiId($id);
        if (!$data) {
            return response()->json(['message' => 'League not found'], 404);
        }
        return response()->json($data);
    }
}
