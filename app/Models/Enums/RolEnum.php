<?php

namespace App\Models\Enums;
enum RolEnum: int{
  case coordinador = 1;
  case administrador = 2;


  public function label(): string
  {
      return match($this) {
          self::coordinador => 'coordinator',
          self::administrador => 'administrator',
      };
  }

  public static function fromLabel(string $label): ?self
  {
      foreach (self::cases() as $case) {
          if ($case->label() === $label) {
              return $case;
          }
      }
      return null;
  }
}