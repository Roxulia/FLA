<?php

namespace App\Http\Controllers;

use App\Services\ApiFetcher;
use App\Services\LiveDataFetcher;
use Illuminate\Cache\ApcStore;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    private ApiFetcher $fetcher;
    private LiveDataFetcher $live_data_fetcher;
    public function __construct(ApiFetcher $fetcher,LiveDataFetcher $live_data_fetcher)
    {
        $this->fetcher = $fetcher;
        $this->live_data_fetcher = $live_data_fetcher;
    }

    public function testLiveAPI()
    {
        return $this->live_data_fetcher->fetchLive();
    }
}
