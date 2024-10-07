<?php

namespace App\Models\Enums;

enum EstadoEnum: int{
  case en_revision = 1;
  case aceptado = 2;
  case evaluado = 3;
  case rechazado = 4;
  case enProceso = 5;
}