<?php

namespace App\Http\Controllers;

use App\DTO\playerDTO;
use App\Repository\playerRepo;
use Illuminate\Http\Request;

class playerController extends Controller
{
    private playerRepo $player_repo;

    public function __construct(playerRepo $repo)
    {
        $this->player_repo = $repo;
    }

    public function createPlayer(Request $request)
    {
        try
        {
            try
            {
                $request->validate([
                    'player_name' => 'required|string',
                    'player_position' => 'required|string',
                    'jersey_number' => 'nullable|integer',
                    'id_from_api' => 'required|integer'
                ]);
            }
            catch (\Illuminate\Validation\ValidationException $e)
            {
                return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
            }
            $existingPlayer = $this->player_repo->getByApiId($request->input('id_from_api'));
            if ($existingPlayer) {
                return response()->json(['message' => 'Player with the same API ID already exists'], 409);
            }
            $match = $this->player_repo->create(playerDTO::fromArray($request->all()));
            return response()->json(['message' => 'Player created successfully', 'data' => $match], 201);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function updatePlayer(int $id,Request $request)
    {
        try
        {
            try
            {
                $request->validate([
                    'player_name' => 'sometimes|required|string',
                    'player_position' => 'sometimes|required|string',
                    'jersey_number' => 'sometimes|nullable|integer',
                    'id_from_api' => 'sometimes|required|integer'
                ]);
            }
            catch (\Illuminate\Validation\ValidationException $e)
            {
                return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
            }
            $existingPlayerByID = $this->player_repo->getById($id);
            $existingPlayerByAPI = $this->player_repo->getByApiId($request->input('id_from_api'));
            if($existingPlayerByAPI->id != $existingPlayerByID->id)
            {
                return response()->json(['message' => 'Player with same API ID existed'],409);
            }
            if($existingPlayerByID == null) {
                return response()->json(['message' => 'Player not Found'], 404);
            }
            $player = $this->player_repo->update($id,playerDTO::fromArray($request->all()));
            return response()->json(['message' => 'Player created successfully', 'data' => $player], 201);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function  getAllPlayers(Request $request)
    {
        $page = $request->get('page', 1);
        $per_page = $request->get('per_page', 10);
        try{
            $page = (int)$page;
            $per_page = (int)$per_page;
            if ($page < 1 || $per_page < 1) {
                return response()->json(['message' => 'Page and per_page must be positive integers'], 400);
            }
            $data = $this->player_repo->getAll($page, $per_page);
            if (!$data) {
                return response()->json(['message' => 'No players found'], 204);
            }
            return response()->json(['message' => 'Players fetched successfully', 'data' => $data], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function getPlayerById(int $id)
    {
        try{
            $data = $this->player_repo->getById($id);
            if (!$data) {
                return response()->json(['message' => 'Player not found'], 404);
            }
            return response()->json($data);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function getPlayerByApiId(int $id)
    {
        try
        {
            $data = $this->player_repo->getByApiId($id);
            if (!$data) {
                return response()->json(['message' => 'Player not found'], 404);
            }
            return response()->json($data);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function deletePlayer(int $id)
    {
        try
        {
            $data = $this->player_repo->getById($id);
            if (!$data) {
                return response()->json(['message' => 'Player not found'], 404);
            }
            if( !$this->player_repo->delete($id))
            {
                return response()->json(['message' => 'Failed to delete player'], 500);
            }
            return response()->json(['message' => 'Player deleted successfully'], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
