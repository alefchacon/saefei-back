<?php

namespace App\Models\Enums;

enum TipoAvisoReservationEnum: int{

  case reservacion_nueva = 5;
  case reservacion_aceptada = 6;
  case reservacion_rechazada = 7;

  /**
   * Transforms values from in the `TipoAvisoEventEnum` range to their Reservation counterparts. 
   * The value `evento_evaluado` (3) is not supported.
   * @param int $value 
   * @return TipoAvisoReservationEnum
   */
  public static function mapFrom(int $value){
    switch($value){
      case 1: return self::reservacion_nueva;
      case 2: return self::reservacion_aceptada;
      case 4: return self::reservacion_rechazada;
      default: throw new \Exception("The parameter `value` must be 1, 2 or 4.");
    }
  }
}