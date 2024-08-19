<?php

namespace App\Mail;

use App\Mail\Mail;
use App\Models\Enums\TipoAvisoEventEnum;
use App\Models\Enums\TipoAvisoReservationEnum;
use App\Models\Evento;
use App\Models\Reservacion;
use App\Models\User;
use App\Utils\DateParser;
use App\Models\Enums\RolEnum;
use App\Models\Enums\EstadoEnum;
use Illuminate\Database\Eloquent\Builder;


class MailProvider{


  private static function boolToCharacter(bool|null $value){
    if (!$value){
      return "No";
    }
    return "Sí";
  }


  public static function getEventNewMail(Evento $event = null, User $user = null) {

    $event->with(["tipo"]);

    $replacement = [
      '{{header}}' => 'El siguiente evento requiere confirmación: ',
      '{{url}}' => env('FRONTEND_URL') . "/eventos/" . $event->id,
      '{{eventName}}' => $event->nombre,
      '{{description}}' => $event->descripcion,
      '{{page}}' => $event->pagina,
      '{{type}}' => $event->tipo->nombre,
      '{{audiences}}' => $event->audiencias,
      '{{scope}}' => $event->ambito,
      '{{axis}}' => $event->eje,
      '{{themes}}' => $event->tematicas,
      '{{start}}' => $event->inicio,
      '{{end}}' => $event->fin,
      '{{numParticipants}}' => $event->numParticipantes,
      '{{requirementsComputerCenter}}' => $event->requisitosCentroComputo,
      '{{needsLivestream}}' => self::boolToCharacter($event->requiereTransmisionEnVivo),
      '{{presidium}}' => $event->presidium,
      '{{decoration}}' => $event->decoracion,
      '{{numParticipantsExternal}}' => $event->numParticipantesExternos,
      '{{needsParking}}' => self::boolToCharacter($event->requiereEstacionamiento),
      '{{needsWeekend}}' => self::boolToCharacter($event->requiereFinDeSemana),
      '{{needsRecords}}' => self::boolToCharacter($event->requiereConstancias),
      '{{media}}' => $event->medios,
      '{{organizer}}' => "$user->nombres $user->apellidoPaterno $user->apellidoMaterno",
      '{{job}}' => $user->puesto,
      '{{email}}' => $user->email,
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


  private static function getReservationReplacement(Reservacion $reservation, TipoAvisoReservationEnum $type){

    $reservation->with(["espacio", "usuario"]);

    $header = "";
    $headers = [
      TipoAvisoReservationEnum::reservacion_nueva->value => "Se solicita reservar el siguiente espacio: ",
      TipoAvisoReservationEnum::reservacion_aceptada->value => "Su reservación ha sido aceptada.",
      TipoAvisoReservationEnum::reservacion_rechazada->value => "Su reservación ha sido rechazada.",
    ];
    $header = $headers[$type->value];

    $replacements = 
    [
      '{{header}}' => $header,
      '{{url}}' => env('FRONTEND_URL') . "/eventos/" . $reservation->id,
      '{{spaceName}}' => $reservation->espacio->nombre,
      '{{start}}' => $reservation->inicio,
      '{{end}}' => $reservation->fin,

      '{{organizer}}' =>  
        $reservation->usuario->nombres . " " .  
        $reservation->usuario->apellidoPaterno . " " . 
        $reservation->usuario->apellidoMaterno,
      '{{job}}' => $reservation->usuario->puesto,
      '{{email}}' => $reservation->usuario->email,
      '{{linkText}}' => "Responder", 
      '{{notes}}' => $reservation->respuesta,
    ];

    $encoded_array = array_map(function($value) {
      return mb_convert_encoding($value,'ISO-8859-1', 'UTF-8');
    }, $replacements);


    return $encoded_array;
  }
  private static function getEventReplacement(Evento $event, TipoAvisoEventEnum $type){

    $event->with(["espacio", "usuario"]);

    $header = "";
    $headers = [
      TipoAvisoEventEnum::evento_nuevo->value => "El siguiente evento requiere ser revisado: ",
      TipoAvisoEventEnum::evento_aceptado->value => "Su evento ha sido aceptado.",
      TipoAvisoEventEnum::evento_rechazado->value => "Su evento ha sido rechazado.",
    ];
    $header = $headers[$type->value];

    $user = $event->usuario;
    $replacements = 
    [
      '{{header}}' => $header,
      '{{notes}}' => $event->observaciones,
      '{{url}}' => env('FRONTEND_URL') . "/eventos/" . $event->id,
      '{{eventName}}' => $event->nombre,
      '{{description}}' => $event->descripcion,
      '{{page}}' => $event->pagina,
      '{{type}}' => $event->tipo->nombre,
      '{{audiences}}' => $event->audiencias,
      '{{scope}}' => $event->ambito,
      '{{axis}}' => $event->eje,
      '{{themes}}' => $event->tematicas,
      '{{start}}' => $event->inicio,
      '{{end}}' => $event->fin,
      '{{numParticipants}}' => $event->numParticipantes,
      '{{requirementsComputerCenter}}' => $event->requisitosCentroComputo,
      '{{needsLivestream}}' => self::boolToCharacter($event->requiereTransmisionEnVivo),
      '{{presidium}}' => $event->presidium,
      '{{decoration}}' => $event->decoracion,
      '{{numParticipantsExternal}}' => $event->numParticipantesExternos,
      '{{needsParking}}' => self::boolToCharacter($event->requiereEstacionamiento),
      '{{needsWeekend}}' => self::boolToCharacter($event->requiereFinDeSemana),
      '{{needsRecords}}' => self::boolToCharacter($event->requiereConstancias),
      '{{media}}' => $event->medios,
      '{{organizer}}' => "$user->nombres $user->apellidoPaterno $user->apellidoMaterno",
      '{{job}}' => $user->puesto,
      '{{email}}' => $user->email,
      '{{linkText}}' => "Responder" 
    ];

    $encoded_array = array_map(function($value) {
      return mb_convert_encoding($value,'ISO-8859-1', 'UTF-8');
    }, $replacements);

    return $encoded_array;
  }


  public static function getReservationMail(Reservacion $reservation = null, TipoAvisoReservationEnum $type) {

    $replacement = self::getReservationReplacement($reservation, $type);
    
    $conditionalContent = '';
    $isReplying = $type->value !== TipoAvisoReservationEnum::reservacion_nueva->value; 

    if ($isReplying) {
        $conditionalContent = mb_convert_encoding(
          "<p><b>Observaciones:</b> {{notes}} </p>", 
          'ISO-8859-1', 
          'UTF-8');        
    }

    $htmlBody = file_get_contents(__DIR__ . '/reservationTemplate.html');
    $htmlBody = str_replace('<!-- CONDITIONAL_CONTENT -->', $conditionalContent, $htmlBody);
    $htmlBody = str_replace(array_keys($replacement), array_values($replacement), $htmlBody);

    $subjects = [
      TipoAvisoReservationEnum::reservacion_nueva->value => "Nueva reservación",
      TipoAvisoReservationEnum::reservacion_aceptada->value => "Reservación aceptada",
      TipoAvisoReservationEnum::reservacion_rechazada->value => "Reservación rechazada",
    ];

    return new Mail(
      mb_convert_encoding($subjects[$type->value], 'ISO-8859-1', 'UTF-8'),
      $htmlBody
    );
  }

  public static function getEventMail(Evento $event = null, TipoAvisoEventEnum $type) {

    $replacement = self::getEventReplacement($event, $type);
    
    $conditionalContent = '';
    $isReplying = $type->value !== TipoAvisoEventEnum::evento_nuevo->value; 

    if ($isReplying) {
        $conditionalContent = mb_convert_encoding(
          "<p><b>Observaciones:</b> {{notes}} </p>", 
          'ISO-8859-1', 
          'UTF-8');        
    }

    $htmlBody = file_get_contents(__DIR__ . '/eventTemplate.html');
    $htmlBody = str_replace('<!-- CONDITIONAL_CONTENT -->', $conditionalContent, $htmlBody);
    $htmlBody = str_replace(array_keys($replacement), array_values($replacement), $htmlBody);

    $subjects = [
      TipoAvisoEventEnum::evento_nuevo->value => "Nuevo evento",
      TipoAvisoEventEnum::evento_aceptado->value => "Evento aceptado",
      TipoAvisoEventEnum::evento_rechazado->value => "Evento rechazado",
    ];

    return new Mail(
      mb_convert_encoding($subjects[$type->value], 'ISO-8859-1', 'UTF-8'),
      $htmlBody
    );
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
  
      MailService::sendEmail(to: $event->usuario, mail: $mail);
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

        "{{ratingAttention}}" => $event->evaluacion->calificacionAtencion."/5",
        "{{ratingAttentionReason}}" => $event->evaluacion->razonCalificacionAtencion,
        "{{improvementsSupport}}" => $event->evaluacion->mejorasApoyo,

        "{{ratingSpace}}" => $event->evaluacion->calificacionEspacio."/5",
        "{{problemsSpace}}" => $event->evaluacion->problemasEspacio,

        "{{ratingComputerCenter}}" => $event->evaluacion->calificacionCentroComputo."/5",
        "{{ratingComputerCenterReason}}" => $event->evaluacion->razonCalificacionCentroComputo,
        "{{ratingResources}}" => $event->evaluacion->calificacionRecursos."/5",
        "{{ratingResourcesReason}}" => $event->evaluacion->razonCalificacionRecursos,
        "{{improvementsResources}}" => $event->evaluacion->mejorasRecursos,

        "{{additional}}" => $event->evaluacion->adicional,
      ];
      
      $htmlBody = file_get_contents(__DIR__ . '/evaluationNew.html');
      $htmlBody = mb_convert_encoding(
        str_replace(array_keys($replacement), array_values($replacement), $htmlBody), 
        'ISO-8859-1', 
        'UTF-8'
      );
      $mail = new Mail(
        "Evento evaluado",
        $htmlBody
      );

      $coordinators = User::where("idRol", RolEnum::coordinador)->get();
      foreach($coordinators as $coordinator){
          MailService::sendEmail(to: $coordinator, mail: $mail);
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


