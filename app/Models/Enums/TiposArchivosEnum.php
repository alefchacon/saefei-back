<?php

namespace App\Models\Enums;

enum TiposArchivosEnum: int{
  case CRONOGRAMA = 1;
  case EVIDENCIA = 2;
  case PUBLICIDAD = 3;

  public function getKey(): string {
    return match($this) {
        self::CRONOGRAMA => "cronograma",
        self::EVIDENCIA => "evidencia",
        self::PUBLICIDAD => "publicidad",
    };
  }
}