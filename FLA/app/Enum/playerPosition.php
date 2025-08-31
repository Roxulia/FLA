<?php

namespace App\Enum;

enum playerPosition : string
{
    case Defender = "Defender";
    case Midfielder = "Midfielder";
    case Attacker = "Attacker";
    case Keeper = "Keeper";
    case Coach = "Coach";
}
