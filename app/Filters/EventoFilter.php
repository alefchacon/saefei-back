<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class EventoFilter extends Apifilter {

    protected $safeParms = [
        'idEstado' => ['eq'], 
        'idUsuario' => ['eq'],
        'inicio' => ['eq'],
        'avisarCoordinador' => ['eq'],
        'avisarUsuario' => ['eq'],
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];

}