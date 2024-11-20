<?php

namespace App\Models\Enums;

enum TipoAvisoEventEnum: int{
  case evento_nuevo = 1;
  case evento_aceptado = 2;
  case evento_editado_organizador = 3;
  case evento_evaluado = 4;
  case evento_editado_coordinador = 8;


}