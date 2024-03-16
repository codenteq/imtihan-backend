<?php

namespace App\Enums;

enum ConditionCategory: string
{
    case MaxScore = 'max_score';
    case Length = 'length';
    case Time = 'time';
    case PenaltyRatio =  'penalty_ratio';
}
