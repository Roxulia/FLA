<?php

namespace App\DTO;

use App\Models\LiveData;

class liveDataDTO extends baseDTO
{
    /**
     * Create a new class instance.
     */
    private int $id;
    private int $live_id;
    private string $home_name;
    private string $home_logo;
    private string $away_name;
    private string $away_logo;
    private int $home_score;
    private int $away_score;
    public function __construct(
        int $id,
        int $live_id,
        string $home_name,
        string $home_logo,
        string $away_name,
        string $away_logo,
        int $home_score,
        int $away_score
    )
    {
        $this->id = $id;
        $this->live_id = $live_id;
        $this->home_name = $home_name;
        $this->home_logo = $home_logo;
        $this->home_score = $home_score;
        $this->away_logo = $away_logo;
        $this->away_name = $away_name;
        $this->away_score = $away_score;
    }

    public static function fromModel(LiveData $model)
    {
        return new self(
            $model->id,
            $model->live_id,
            $model->home_name,
            $model->home_logo,
            $model->away_name,
            $model->away_logo,
            $model->home_score,
            $model->away_score
        );
    }
}
