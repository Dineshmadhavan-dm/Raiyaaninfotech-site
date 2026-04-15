<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('attendance:mark-absent')
    ->timezone('Asia/Kolkata')
    ->everyMinute();
