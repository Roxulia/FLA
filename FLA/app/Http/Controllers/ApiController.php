<?php

namespace App\Http\Controllers;

use App\Services\ApiFetcher;
use Illuminate\Cache\ApcStore;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    private ApiFetcher $fetcher;
    public function __construct(ApiFetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }
    public function testLeagueAPI()
    {
        return $this->fetcher->storeLeagues();
    }
}
