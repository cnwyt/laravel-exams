<?php

namespace Cnwyt\Exams\Models;

use Illuminate\Support\Facades\Facade;

class ExamsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ExamsFacade';
    }
}