<?php

namespace App\Mail;

use App\Mail\Mail;
use App\Models\Evento;


class MailFactory{



  public static function GetEventNewMail(Evento $event) {
    return new Mail(
      "Se ha registrado un nuevo evento",
      "Se ha registrado el siguiente evento."
    );
  }
  public static function GetEventAcceptedMail(
    Evento $event, 
    string $subject = "Su evento ha sido agendado",
    string $body = "Su evento ha sido agendado."
    
    ) {
    return new Mail(
      $subject,
      $body
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


