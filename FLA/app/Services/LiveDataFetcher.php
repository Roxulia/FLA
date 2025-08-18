<?php

namespace App\Services;

use App\DTO\liveDataDTO;
use App\Repository\liveDataRepo;
use Illuminate\Support\Facades\Http;

class LiveDataFetcher
{
    /**
     * Create a new class instance.
     */
    protected string $baseUrl;
    protected string $key;
    protected string $host;
    protected liveDataRepo $live_data_repo;

    public function __construct(string $baseUrl, string $key, string $host)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->key = $key;
        $this->host = $host;
        $this->live_data_repo = new liveDataRepo();
    }

    public function fetchLive()
    {
        $response = Http::withHeaders([
            'x-rapidapi-host' => $this->host,
            'x-rapidapi-key'  => $this->key,
        ])->get("{$this->baseUrl}");

        if ($response->failed()) {
            throw new \Exception('Failed to fetch live data.');
        }
        $json = $response->json(); // Decode response to array
        $live = $json['result'] ?? []; // Safely get leagues or empty array

        return $live;
    }

    public function storeLiveData()
    {
        $data = $this->fetchLive();
        $record = 0;
        foreach($data as $item)
        {
            if($item['status'] != "Live")
            {
                continue;
            }
            else
            {
                $home_score = null;
                $away_score = null;

                if (!empty($item['score']) && strpos($item['score'], 'vs') !== false) {
                    $scores = explode('vs', $item['score']);
                    $home_score = isset($scores[0]) ? (int) trim($scores[0]) : 0;
                    $away_score = isset($scores[1]) ? (int) trim($scores[1]) : 0;
                }
                $this->live_data_repo->store(new liveDataDTO(0,$item['id'],$item['home_name'],$item['home_flag'],$item['away_name'],$item['away_flag'],$home_score,$away_score));
                $record++;
            }
        }
        return "{$record} records saved";
    }
}
