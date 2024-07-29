<?php

namespace App\Mail;

use App\Mail\Mail;
use App\Models\Evento;
use App\Models\User;
use App\Utils\DateParser;


class MailFactory{




  public static function GetEventNewMail(Evento $event = null, User $user = null) {

    $replacement = [
      '{{header}}' => 'El siguiente evento requiere confirmación',
      '{{url}}' => env('FRONTEND_URL') . "/eventos/" . $event->id,
      '{{eventName}}' => $event->nombre,
      '{{organizer}}' => $user->nombres . " " .  $user->apellidoPaterno . " " . $user->apellidoMaterno,
      '{{job}}' => $user->puesto,
      '{{dates}}' => DateParser::translateDateString($event->inicio, $event->fin),
    ];


    $htmlBody = file_get_contents(__DIR__ . '/eventNew.html');
    $htmlBody = str_replace(array_keys($replacement), array_values($replacement), $htmlBody);
    return new Mail(
      "Nuevo evento",
      $htmlBody
    );
  }
  public static function GetEventAcceptedMail(
    Evento $event, 
    User $user = null,
    string $subject = "Su evento ha sido agendado",
    string $body = "Su evento ha sido agendado."
    
    ) {
    $replacement = [
      '{{header}}' => 'Su evento ha sido programado',
      '{{url}}' => env('FRONTEND_URL') . "/eventos/" . $event->id,
      '{{eventName}}' => $event->nombre,
      '{{organizer}}' => $user->nombres . " " .  $user->apellidoPaterno . " " . $user->apellidoMaterno,
      '{{job}}' => $user->puesto,
    ];

    $htmlBody = file_get_contents(__DIR__ . '/eventNew.html');
    $htmlBody = str_replace(array_keys($replacement), array_values($replacement), $htmlBody);
    return new Mail(
      $subject,
      $htmlBody
    );
  }
  public static function GetEventDeniedMail(
    Evento $event, 
    string $subject = "Su evento ha sido rechazado",
    string $body = "Su evento ha sido rechazado."
    
    ) {
    return new Mail(
      $subject,
      $body
    );
  }
  

}


