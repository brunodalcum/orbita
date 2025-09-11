<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Configurar scheduler para lembretes
Schedule::command('reminders:process')
    ->everyMinute()
    ->withoutOverlapping(5) // Evitar sobreposição por 5 minutos
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/reminders-scheduler.log'));
