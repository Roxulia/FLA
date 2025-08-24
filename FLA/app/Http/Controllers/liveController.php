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
    public function getAll()
    {
        return response()->json($this->live_data_repo->getAll());
    }
}
