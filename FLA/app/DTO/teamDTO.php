<?php

namespace App\DTO;

use App\Models\Teams;
use DateTime;

class teamDTO extends baseDTO
{
    /**
     * Create a new class instance.
     */

    private int $team_id;
    private string $fullName;
    private string $shortForm;
    private string $code;
    private string $country;
    private string $city;
    private string $stadium_name;
    private DateTime $found_year;
    private string $logo;
    private bool $is_national;
    private bool $is_active;
    private int $id_from_api;

    public function __construct(
        int $team_id,
        string $fullName,
        ?string $shortForm,
        ?string $code,
        ?string $country,
        ?string $city,
        ?string $stadium_name,
        ?DateTime $found_year,
        string $logo,
        ?bool $is_national,
        ?bool $is_active,
        int $id_from_api
    )
    {
        $this->team_id = $team_id;
        $this->fullName = $fullName;
        $this->shortForm = $shortForm;
        $this->code = $code;
        $this->country = $country;
        $this->city = $city;
        $this->stadium_name = $stadium_name;
        $this->found_year = $found_year;
        $this->logo = $logo;
        $this->is_national = $is_national;
        $this->is_active = $is_active;
        $this->id_from_api = $id_from_api;
    }

    public static function fromModel(Teams $team) : self
    {
        return new self(
            $team->team_id,
            $team->fullName,
            $team->shortForm ,
            $team->code,
            $team->country,
            $team->city,
            $team->stadium_name,
            $team->found_year,
            $team->logo,
            $team->is_national,
            $team->is_active,
            $team->id_from_api
        );
    }

    public static function fromArray($data) : self
    {
        return new self(
            $data['team_id'] ?? 0,
            $data['name'],
            $data['shortform'] ?? null,
            $data['code'] ?? null,
            $data['country'] ?? null,
            $data['city'] ?? null,
            $data['stadium_name'] ?? null,
            isset($data['found_year'])? new DateTime($data['found_year']) : null,
            $data['logo'] ?? null,
            $data['is_national'] ?? false,
            $data['is_active'] ?? false,
            $data['id_from_api']
        );
    }

}
