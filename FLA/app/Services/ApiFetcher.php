<?php

namespace App\Services;

use App\Models\league_position;
use Illuminate\Support\Facades\Http;
use App\Models\Leagues;
use App\Models\Matches;
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
        $players = $json['response']['list']['squad'] ?? [];


        return $players;
    }

    public function fetchMatchesByDate($date)
    {
        $response = Http::withHeaders([
            'x-rapidapi-host' => $this->host,
            'x-rapidapi-key'  => $this->key,
        ])->get("{$this->baseUrl}/football-get-matches-by-date?date={$date}");

        if ($response->failed()) {
            throw new \Exception('Failed to fetch players.');
        }
        $json = $response->json(); // Decode response to array
        $matches = $json['response']['matches'] ?? [];

        return $matches;
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
                $team = Teams::updateOrCreate(

                    [
                        'team_fullname' => $item['name'],
                        'team_shortform'=>$item['shortName'],
                        'team_code'=>Null,
                        'country'=>Null,
                        'city'=>Null,
                        'stadium_name'=>Null,
                        'found_year'=>Null,
                        'logo'=>$item['logo'],
                        'id_from_api'=>$item['id'],
                    ]
                );
                $team_id = $team->team_id;
                $scores = explode('-',$item['scoreStr']);

                league_position::Create(
                    [
                        'team_id' => $team_id,
                        'league_id' =>$i,
                        'team_position'=>$item['idx'],
                        'played_matches'=>$item['played'],
                        'wins'=>$item['wins'],
                        'losses'=>$item['losses'],
                        'draws'=>$item['draws'],
                        'goal_given' => $scores[1],
                        'goal_achieved'=>$scores[0],
                        'points' => $item['pts']

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
            $recordLen = $this->preprocessPlayers($data);
            $recordCount += $recordLen;
        }
        return $recordCount . 'Records Saved';
    }

    public function storeMatches($date)
    {
        $teamrepo = new teamRepo();
        $leaguerepo = new leagueRepo();
        $recordCount = 0;
        $data = $this->fetchMatchesByDate($date);
        foreach($data as $item)
        {
            $home_team = $teamrepo->getTeamByApiId($item['home']['id']);
            $away_team = $teamrepo->getTeamByApiId($item['away']['id']);
            $league = $leaguerepo->getLeagueByApiId($item['leagueId']);
            $time = str_split($item['time'],11);
            if(!$home_team || !$away_team || !$league){
                continue;
            }
            $home_team_id = $home_team->team_id;
            $away_team_id = $away_team->team_id;
            $league_id = $league->league_id;
            Matches::updateOrCreate(
                [
                    'home_team_id' => $home_team_id,
                    'away_team_id' => $away_team_id,
                    'date' => $date,
                    'time' => $time[1],
                    'score' => $item['status']['scoreStr'],
                    'league_id'=>$league_id,
                    'id_from_api'=>$item['id']
                ]
            );
            $recordCount++;
        }
        return $recordCount . "Saved";
    }

    public function preprocessPlayers($data)
    {
        $recordLen = 0;
        foreach($data as $item)
        {
            $members = $item['members'];
            foreach($members as $m)
            {
                Players::updateOrCreate(
                    [
                        'player_name' => $m['name'],
                        'player_position' => $m['role']['fallback'],
                        'jersey_number'=>$m['shirtNumber'],
                        'id_from_api'=>$m['id']
                    ]
                );
            }
            $recordLen += count($members);
        }
        return $recordLen;
    }
}
