<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

\Illuminate\Support\Facades\Schedule::command('sources:run')
    ->everyFourHours()
    ->between("09:00", '22:30')
    ->withoutOverlapping();
