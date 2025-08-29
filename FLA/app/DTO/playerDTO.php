<?php

namespace App\DTO;

use App\Models\Players;

class playerDTO extends baseDTO
{
    /**
     * Create a new class instance.
     */
    private int $player_id;
    private string $player_name;
    private string $player_position;
    private int $jersey_number;
    private int $id_from_api;
    public function __construct(
        ?int $player_id,
        ?string $player_name,
        ?string $player_position,
        ?int $jersey_number,
        ?int $id_from_api
    )
    {
        $this->player_id = $player_id;
        $this->player_name = $player_name;
        $this->player_position = $player_position;
        $this->jersey_number = $jersey_number;
        $this->id_from_api = $id_from_api;
    }

    public static function fromModel(Players $model)
    {
        return new self(
            $model->player_id,
            $model->player_name,
            $model->player_position,
            $model->jersey_number,
            $model->id_from_api
        );
    }

    public static function  fromArray(array $data) : self
    {
        return new self(
            $data['id'] ?? 0,
            $data['name'],
            $data['position'] ?? null,
            $data['jersey_number'] ?? null,
            $data['id_from_api'] ?? null
        );
    }

}
