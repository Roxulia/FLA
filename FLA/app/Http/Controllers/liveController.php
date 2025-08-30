<?php

namespace App\Http\Controllers;

use App\Repository\liveDataRepo;
use Illuminate\Http\Request;

class liveController extends Controller
{
    private liveDataRepo $live_data_repo;
    public function __construct(liveDataRepo $live_data_repo)
    {
        $this->live_data_repo = $live_data_repo;
    }
    public function getAllLives(Request $request)
    {
        $page = $request->get('page', 1);
        $per_page = $request->get('per_page', 10);
        try{
            $page = (int)$page;
            $per_page = (int)$per_page;
            if ($page < 1 || $per_page < 1) {
                return response()->json(['message' => 'Page and per_page must be positive integers'], 400);
            }
            $data = $this->live_data_repo->getAllUsingPagination($page, $per_page);
            if (!$data) {
                return response()->json(['message' => 'No lives found'], 204);
            }
            return response()->json(['message' => 'Lives fetched successfully', 'data' => $data], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
