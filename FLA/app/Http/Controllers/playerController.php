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
        return $this->player_repo->getAllPlayers();
    }

    public function getPlayerByApiId(int $id)
    {
        return $this->player_repo->getPlayerByApiId($id);
    }
}
