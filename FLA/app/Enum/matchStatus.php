<?php

namespace App\Enum;

enum matchStatus :string
{
    case scheduled = "scheduled";
    case live = "live";
    case finished = "finished";
    case postponed = "postponed";
    case cancelled = "cancelled";
}
