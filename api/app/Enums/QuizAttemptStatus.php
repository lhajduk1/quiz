<?php

namespace App\Enums;

enum QuizAttemptStatus: string
{
    case STARTED = 'started';
    case FINISHED = 'finished';
}
