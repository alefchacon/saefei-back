<?php

namespace App\Models\Enums;
enum RolEnum: int{
  case organizador = 1;
  case administrador_espacios = 2;
  case tecnico_academico = 3;
  case responsable = 4;
  case coordinador = 5;
}