<?php

use App\Mail\MailProvider;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

use App\Models\Evento;



Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    $event = Evento::where("id", 4)->with(['evaluacion', 'usuario'])->first();
        
    MailProvider::SendEvaluationNewMail(event: $event);
})->daily();