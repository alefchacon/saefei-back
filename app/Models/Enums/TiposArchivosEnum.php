<?php

namespace App\Models\Enums;

enum TiposArchivosEnum: int{
  case CRONOGRAMA = 1;
  case EVIDENCIA = 2;
  case PUBLICIDAD = 3;
}