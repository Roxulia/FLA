<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Leagues;
use App\Models\Players;
use App\Models\Teams;
use App\Repository\leagueRepo;
use App\Repository\teamRepo;
use SebastianBergmann\Type\NullType;

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

    public function fetchTeamsByLeagueId($id)
    {
        $response = Http::withHeaders([
            'x-rapidapi-host' => $this->host,
            'x-rapidapi-key'  => $this->key,
        ])->get("{$this->baseUrl}/football-get-list-all-team?leagueid={$id}");

        if ($response->failed()) {
            throw new \Exception('Failed to fetch teams.');
        }
        $json = $response->json(); // Decode response to array
        $teams = $json['response']['teams'] ?? []; // Safely get leagues or empty array


        return $teams;
    }

    public function fetchPlayersByTeamId($id)
    {
        $response = Http::withHeaders([
            'x-rapidapi-host' => $this->host,
            'x-rapidapi-key'  => $this->key,
        ])->get("{$this->baseUrl}/football-get-list-player?teamid={$id}");

        if ($response->failed()) {
            throw new \Exception('Failed to fetch players.');
        }
        $json = $response->json(); // Decode response to array
        $players = $json['response']['players'] ?? []; // Safely get leagues or empty array


        return $players;
    }

    public function storeLeagues()
    {
        $data = $this->fetchLeagues();
        // Example: store data
        foreach ($data as $item) {
            Leagues::updateOrCreate(

                [
                    'fullname' => $item['name'],
                    'shortform'=> Null,
                    'code'=> Null,
                    'country'=> Null,
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

    public function storeTeams()
    {
        $leaguerepo = new leagueRepo();
        $recordCount = 0;
        $idList = $leaguerepo->getAllAPILeagueID();
        foreach($idList as $i){
            $data = $this->fetchTeamsByLeagueId($i);
            // Example: store data
            foreach ($data as $item) {
                Teams::updateOrCreate(

                    [
                        'team_fullname' => $item['name'],
                        'team_shortform'=>Null,
                        'team_code'=>Null,
                        'country'=>Null,
                        'city'=>Null,
                        'stadium_name'=>Null,
                        'found_year'=>Null,
                        'logo'=>$item['logo'],
                        'id_from_api'=>$item['id'],
                    ]
                );
            }
            $recordCount += count($data);
        }
        return $recordCount . 'Records Saved';
    }

    public function storePlayers()
    {
        $teamrepo = new teamRepo();
        $recordCount = 0;
        $idList = $teamrepo->getAllApiId();
        foreach($idList as $i){
            $data = $this->fetchPlayersByTeamId($i);
            // Example: store data
            foreach ($data as $item) {
                Players::updateOrCreate(

                    [
                        'player_name'=>$item['name'],
                        'player_position'=>Null,
                        'jersey_number'=>Null,
                        'id_from_api'=>$item['id'],
                    ]
                );
            }
            $recordCount += count($data);
        }
        return $recordCount . 'Records Saved';
    }
}
