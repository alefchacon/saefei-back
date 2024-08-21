<?php

namespace App\Models\Enums;

enum TipoAvisoEventEnum: int{
  case evento_nuevo = 1;
  case evento_aceptado = 2;
  case evento_evaluado = 3;
  case evento_rechazado = 4;


}