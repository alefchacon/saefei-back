<?php

use App\Mail\MailService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

use App\Models\Evento;



Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    $event = Evento::where("id", 4)->with(['evaluacion', 'usuario'])->first();
        
    MailService::SendEvaluationNewMail(event: $event);
})->everyMinute();