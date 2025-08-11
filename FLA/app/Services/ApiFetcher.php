<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Leagues;

class ApiFetcher
{
    /**
     * Create a new class instance.
     */
    protected $baseUrl;
    protected $key;
    protected $host;

    public function __construct(string $baseUrl, string $key, string $host)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->key = $key;
        $this->host = $host;
    }

    public function fetchLeagues()
    {
        $response = Http::withHeaders([
            'x-rapidapi-host' => $this->host,
            'x-rapidapi-key'  => $this->key,
        ])->get("{$this->baseUrl}/football-get-all-leagues");

        if ($response->failed()) {
            throw new \Exception('Failed to fetch leagues.');
        }
        $json = $response->json(); // Decode response to array
        $leagues = $json['response']['leagues'] ?? []; // Safely get leagues or empty array


        return $leagues;
    }

    public function storeLeagues()
    {
        $data = $this->fetchLeagues();
        // Example: store data
        foreach ($data as $item) {
            Leagues::updateOrCreate(

                [
                    'fullname' => $item['name'],
                    'shortform'=> "",
                    'code'=> "",
                    'country'=> "",
                    'type'=> Null,
                    'tier'=> Null,
                    'season_start'=> Null,
                    'season_end'=> Null,
                    'current_season'=> Null,
                    'logo'=> $item['logo'],
                    'id_from_api'=> $item['id']
                ]
            );
        }

        return count($data) . ' records saved.';
    }
}
