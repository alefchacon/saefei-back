<?php

namespace App\Utils;

class DateParser {

  public static function translateDateString(string $start, string $end){

    $months = [
      1 => "enero",
      2 => "febrero",
      3 => "marzo",
      4 => "abril",
      5 => "mayo",
      6 => "junio",
      7 => "julio",
      8 => "agosto",
      9 => "septiembre",
      10 => "octubre",
      11 => "noviembre",
      12 => "diciembre",
    ];

    $dateStart = (new \DateTime($start));
    $dateEnd = (new \DateTime($end));

    $monthStart = (int) ($dateStart)->format("m");
    $monthEnd = (int) ($dateEnd)->format("m");

    $sameMonth = $monthStart === $monthEnd;
    $sameDay = (int) $dateStart->format("d") === (int) $dateEnd->format("d");
    
    $dateString = (int) $dateStart->format("d");

    if (!$sameMonth){
        $dateString = 
            $dateString . 
            " de " . 
            $months[$monthStart] . 
            " al " .
            (int) $dateEnd->format("d") ;
    }
    
    if ($sameMonth && !$sameDay){
        $dateString = 
            $dateString . 
            " al " .
            (int) $dateEnd->format("d") ;
    }

    $dateString = 
        $dateString .   
        " de " . 
        $months[$monthEnd];

    return $dateString;
  }
}