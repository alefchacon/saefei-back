<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class ReservacionFilter extends Apifilter {

    protected $safeParms = [
        'idEvento' => ['eq', 'is'], 
        'idUsuario' => ['eq'], 
        'idEstado' => ['eq', 'not'], 
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'not' => '!=',
        'is' => 'IS',
    ];

}