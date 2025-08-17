<?php

namespace App\DTO;

use App\Models\Leagues;
use DateTime;

class leagueDTO extends baseDTO
{
    /**
     * Create a new class instance.
     */
    private int $league_id;
    private string $fullName;
    private string $shortForm;
    private string $code;
    private string $country;
    private string $type;
    private string $tier;
    private DateTime $season_start;
    private DateTime $season_end;
    private int $current_season;
    private string $logo;
    private int $id_from_api;
    public function __construct(
        int $league_id,
        string $fullName,
        ?string $shortForm,
        ?string $code,
        ?string $country,
        ?string $type,
        ?string $tier,
        ?DateTime $season_start,
        ?DateTime $season_end,
        ?int $current_season,
        string $logo,
        int $id_from_api,
    )
    {
        $this->league_id = $league_id;
        $this->fullName = $fullName;
        $this->shortForm = $shortForm;
        $this->code = $code;
        $this->country = $country;
        $this->type = $type;
        $this->tier = $tier;
        $this->season_start = $season_start;
        $this->season_end = $season_end;
        $this->current_season = $current_season;
        $this->logo = $logo;
        $this->id_from_api = $id_from_api;
    }

    public static function fromModel(Leagues $league) : self
    {
        return new self(
            $league->league_id,
            $league->fullName,
            $league->shortForm,
            $league->code,
            $league->country,
            $league->type,
            $league->tier,
            $league->season_start,
            $league->season_end,
            $league->current_season,
            $league->logo,
            $league->id_from_api
        );
    }


}
