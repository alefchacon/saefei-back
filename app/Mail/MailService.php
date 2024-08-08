<?php

namespace App\Mail;

use App\Mail\Mail;
use App\Models\Evento;
use App\Models\User;
use App\Utils\DateParser;
use App\Models\Enums\RolEnum;
use App\Models\Enums\EstadoEnum;
use Illuminate\Database\Eloquent\Builder;


class MailService{




  public static function GetEventNewMail(Evento $event = null, User $user = null) {

    $replacement = [
      '{{header}}' => 'El siguiente evento requiere confirmación: ',
      '{{url}}' => env('FRONTEND_URL') . "/eventos/" . $event->id,
      '{{eventName}}' => $event->nombre,
      '{{description}}' => $event->descripcion,
      '{{organizer}}' => "$user->nombres $user->apellidoPaterno $user->apellidoMaterno",
      '{{job}}' => $user->puesto,
      '{{dates}}' => DateParser::translateDateString($event->inicio, $event->fin),
      '{{linkText}}' => "Responder" 
    ];
    
    
    $htmlBody = file_get_contents(__DIR__ . '/eventNew.html');
    $htmlBody = str_replace(array_keys($replacement), array_values($replacement), $htmlBody);
    return new Mail(
      "Nuevo evento",
      $htmlBody
    );
  }
  public static function sendEventReplyMail(
    Evento $event, 
    ) {
    $replyStatus = " ? ";
    if ($event->idEstado === EstadoEnum::aceptado->value) {
      $replyStatus = "aceptado";
    } else if ($event->idEstado === EstadoEnum::rechazado->value) {
      $replyStatus = "rechazado";
    }

    $replacement = [
      '{{header}}' => "Su evento ha sido $replyStatus: ",
      '{{url}}' => env('FRONTEND_URL') . "/eventos/" . $event->id,
      '{{eventName}}' => $event->nombre,
      '{{description}}' => $event->descripcion,
      '{{dates}}' => DateParser::translateDateString($event->inicio, $event->fin),
      '{{reply}}' => $event->respuesta
    ];
    
    $htmlBody = file_get_contents(__DIR__ . '/eventReply.html');
    $htmlBody = str_replace(array_keys($replacement), array_values($replacement), $htmlBody);


    $mail = new Mail(
      "Evento $replyStatus",
      $htmlBody
    );

    Mailer::sendEmail(to: $event->usuario, mail: $mail);
  }
  public static function sendEvaluationPendingMail() {
    $events = Evento::getUnevaluatedEvents();

    if ($events->count() === 0){
      return;
  }

    foreach($events as $event){
      $replacement = [
        '{{url}}' => env('FRONTEND_URL') . "/eventos/" . $event->id,
        '{{eventName}}' => $event->nombre,
      ];
      
      $htmlBody = file_get_contents(__DIR__ . '/evaluationPending.html');
      $htmlBody = mb_convert_encoding(
        str_replace(array_keys($replacement), array_values($replacement), $htmlBody), 
        'ISO-8859-1', 
        'UTF-8'
      );
  
  
      $mail = new Mail(
        mb_convert_encoding("Evalúe su evento", 'ISO-8859-1', 'UTF-8'),
        $htmlBody
      );
  
      Mailer::sendEmail(to: $event->usuario, mail: $mail);
    }

  }

  private static function getRatingString(int $rating){
    $ratingString = "";
    for ($i = 0; $i<$rating; $i++){
        $ratingString =  $ratingString . "★";
    }
    for ($i = 0; $i<5 - $rating; $i++){
        $ratingString =  $ratingString . "☆";
    }
    return $ratingString;
  }

  public static function sendEvaluationNewMail(
    Evento $event, 
    ) {
      
      $replacement = [
        '{{header}}' => 'Su evento ha sido agendado: ',
        '{{url}}' => env('FRONTEND_URL') . "/eventos/" . $event->id,
        '{{eventName}}' => $event->nombre,
        '{{organizer}}' => $event->usuario->nombres . " " .  $event->usuario->apellidoPaterno . " " . $event->usuario->apellidoMaterno,
        '{{job}}' => $event->usuario->puesto,
        '{{linkText}}' => "Ver evento", 

        "{{ratingAttention}}" => self::getRatingString($event->evaluacion->calificacionAtencion),
        "{{ratingAttentionReason}}" => $event->evaluacion->razonCalificacionAtencion,
        "{{improvementsSupport}}" => $event->evaluacion->mejorasApoyo,

        "{{ratingSpace}}" => self::getRatingString($event->evaluacion->calificacionEspacio),
        "{{problemsSpace}}" => $event->evaluacion->problemasEspacio,

        "{{ratingComputerCenter}}" => self::getRatingString($event->evaluacion->calificacionCentroComputo),
        "{{ratingComputerCenterReason}}" => $event->evaluacion->razonCalificacionCentroComputo,
        "{{ratingResources}}" => self::getRatingString($event->evaluacion->calificacionRecursos),
        "{{ratingResourcesReason}}" => $event->evaluacion->razonCalificacionRecursos,
        "{{improvementsResources}}" => $event->evaluacion->mejorasRecursos,

        "{{additional}}" => $event->evaluacion->adicional,
      ];
      
      $htmlBody = file_get_contents(__DIR__ . '/evaluationNew.html');
      $htmlBody = str_replace(array_keys($replacement), array_values($replacement), $htmlBody);

      $mail = new Mail(
        "Evento evaluado",
        $htmlBody
      );

      $coordinators = User::where("idRol", RolEnum::coordinador)->get();
      foreach($coordinators as $coordinator){
          Mailer::sendEmail(to: $coordinator, mail: $mail);
      }

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


