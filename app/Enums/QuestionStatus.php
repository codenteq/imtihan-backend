<?php

namespace App\Enums;

enum QuestionStatus: string
{
    case Draft = 'draft';
    case Pending = 'pending';
    case Published = 'published';
}
