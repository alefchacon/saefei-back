<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class SolicitudEspacioFilter extends Apifilter {

    protected $safeParms = [
        'idEvento' => ['eq', 'is'], 
        'idUsuario' => ['eq'], 
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'is' => 'IS',
    ];

}