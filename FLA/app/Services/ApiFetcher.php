<?php

namespace App\Services;

use App\DTO\leagueDTO;
use App\DTO\leaguePositionDTO;
use App\DTO\matchDTO;
use App\DTO\playerDTO;
use App\DTO\teamDTO;
use App\Models\league_position;
use Illuminate\Support\Facades\Http;
use App\Models\Leagues;
use App\Models\Matches;
use App\Models\Players;
use App\Models\Teams;
use App\Repository\leagueRepo;
use App\Repository\leagueTableRepo;
use App\Repository\matchRepo;
use App\Repository\playerRepo;
use App\Repository\teamRepo;
use Illuminate\Support\Facades\Date;
use Ramsey\Uuid\Type\Time;
use SebastianBergmann\Type\NullType;

class ApiFetcher
{
    /**
     * Create a new class instance.
     */
    protected $baseUrl;
    protected $key;
    protected $host;
    protected $leaguerepo;
    protected $teamrepo;
    protected $matchrepo;
    protected $league_pos_repo;
    protected $playerrepo;

    public function __construct(string $baseUrl, string $key, string $host)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->key = $key;
        $this->host = $host;
        $this->leaguerepo = new leagueRepo();
        $this->teamrepo = new teamRepo();
        $this->matchrepo = new matchRepo();
        $this->league_pos_repo = new leagueTableRepo();
        $this->playerrepo = new playerRepo();
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
            $this->leaguerepo->create(new leagueDTO(0,$item['name'],null,null,null,null,null,null,null,null,$item['logo'],$item['id']));
        }

        return count($data) . ' records saved.';
    }

    public function storeTeams()
    {

        $recordCount = 0;
        $idList = $this->leaguerepo->getAllAPILeagueID();
        foreach($idList as $i){
            $data = $this->fetchTeamsByLeagueId($i);
            $league = $this->leaguerepo->getLeagueByApiId($i);
            // Example: store data
            foreach ($data as $item) {
                $team = $this->teamrepo->create(new teamDTO(0,$item['name'],$item['shortName'],null,null,null,null,null,$item['logo'],null,null,$item['id_from_api']));
                $team_id = $team->team_id;
                $scores = explode('-',$item['scoreStr']);
                $this->league_pos_repo->create(new leaguePositionDTO(0,$team_id,$i,$league->current_season,$item['idx'],$item['played'],$item['wins'],$item['losses'],$item['draws'],$scores[1],$scores[0],$item['points'],null,null,null,null,null,null));
            }
            $recordCount += count($data);
        }
        return $recordCount . 'Records Saved';
    }


    public function storePlayers()
    {

        $recordCount = 0;
        $idList = $this->teamrepo->getAllApiId();
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

        $recordCount = 0;
        $data = $this->fetchMatchesByDate($date);
        foreach($data as $item)
        {
            $home_team = $this->teamrepo->getByApiId($item['home']['id']);
            $away_team = $this->teamrepo->getByApiId($item['away']['id']);
            $league = $this->leaguerepo->getLeagueByApiId($item['leagueId']);
            $time = str_split($item['time'],11);
            if(!$home_team || !$away_team || !$league){
                continue;
            }
            $home_team_id = $home_team->team_id;
            $away_team_id = $away_team->team_id;
            $league_id = $league->league_id;
            $score = $item['status']['scoreStr'];
            $this->matchrepo->create(new matchDTO(0,$home_team_id,$away_team_id,$date,$time[1],$score,null,$league_id,$item['id_from_api']));
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
                $this->playerrepo->create(new playerDTO(0,$m['name'],$m['role']['fallback'],$m['shirtNumber'],$m['id']));

            }
            $recordLen += count($members);
        }
        return $recordLen;
    }
}
