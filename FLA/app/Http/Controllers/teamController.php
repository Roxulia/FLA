<?php

namespace App\Http\Controllers;

use App\DTO\teamDTO;
use App\Repository\teamRepo;
use Illuminate\Http\Request;


class teamController extends Controller
{
    private teamRepo $team_repo;

    public function __construct(teamRepo $repo)
    {
        $this->team_repo = $repo;
    }

    public function createTeam(Request $request)
    {
        try
        {
            try
            {
                $request->validate([
                    'fullname'=>'required|string|max:50',
                    'shortform' => 'nullable|string',
                    'code' => 'nullable|string',
                    'country' => 'nullable|string',
                    'city' => 'nullable|string',
                    'stadium_name' => 'nullable|string',
                    'found_year' => 'nullable|date',
                    'logo'=> 'nullable|string',
                    'is_national' => 'nullable',
                    'is_active' => 'nullable',
                    'id_from_api' => 'required|integer|unique : teams,id_from_api',
                ]);
            }
            catch (\Illuminate\Validation\ValidationException $e)
            {
                return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
            }
            $existingMatch = $this->team_repo->getByApiId($request->input('id_from_api'));
            if ($existingMatch) {
                return response()->json(['message' => 'Team with the same API ID already exists'], 409);
            }
            $match = $this->team_repo->create(teamDTO::fromArray($request->all()));
            return response()->json(['message' => 'Team created successfully', 'data' => $match], 201);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateTeam(int $id,Request $request)
    {
        try
        {
            try
            {
                $request->validate([
                    'fullname'=>'sometimes|required|string|max:50',
                    'shortform' => 'sometimes|nullable|string',
                    'code' => 'sometimes|nullable|string',
                    'country' => 'sometimes|nullable|string',
                    'city' => 'sometimes|nullable|string',
                    'stadium_name' => 'sometimes|nullable|string',
                    'found_year' => 'sometimes|nullable|date',
                    'logo'=> 'sometimes|nullable|string',
                    'is_national' => 'sometimes|nullable',
                    'is_active' => 'sometimes|nullable',
                    'id_from_api' => 'sometimes|required|integer|unique : teams,id_from_api',
                ]);
            }
            catch (\Illuminate\Validation\ValidationException $e)
            {
                return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
            }
            $existingMatch = $this->team_repo->getById($id);
            if ($existingMatch == null) {
                return response()->json(['message' => 'Team not Found'], 404);
            }
            $match = $this->team_repo->update($id,teamDTO::fromArray($request->all()));
            return response()->json(['message' => 'Team updated successfully', 'data' => $match], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function deleteTeam(int $id)
    {
        try
        {
            $match = $this->team_repo->getById($id);
            if (!$match) {
                return response()->json(['message' => 'Team not found'], 404);
            }
            if( !$this->team_repo->delete($id))
            {
                return response()->json(['message' => 'Failed to delete team'], 500);
            }
            return response()->json(['message' => 'Team deleted successfully'], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function  getAllTeams(Request $request)
    {
        $page = $request->get('page', 1);
        $per_page = $request->get('per_page', 10);
        try{
            $page = (int)$page;
            $per_page = (int)$per_page;
            if ($page < 1 || $per_page < 1) {
                return response()->json(['message' => 'Page and per_page must be positive integers'], 400);
            }
            $data = $this->team_repo->getAll($page, $per_page);
            if (!$data) {
                return response()->json(['message' => 'No teams found'], 204);
            }
            return response()->json(['message' => 'Teams fetched successfully', 'data' => $data], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function getTeamById(int $id)
    {
        try{
            $data = $this->team_repo->getById($id);
            if (!$data) {
                return response()->json(['message' => 'Team not found'], 404);
            }
            return response()->json($data);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function getTeamByApiId(int $id)
    {
        try
        {
            $data = $this->team_repo->getByApiId($id);
            if (!$data) {
                return response()->json(['message' => 'Team not found'], 404);
            }
            return response()->json($data);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
