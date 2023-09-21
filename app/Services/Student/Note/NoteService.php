<?php

namespace App\Services\Student\Note;

use App\Models\Note;
use App\Services\Base\BaseService;

class NoteService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Note::class);
    }
}
