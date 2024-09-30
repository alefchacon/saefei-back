<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiFilter {

    protected $safeParms = [];

    protected $columnMap = [
        'anio' => 'YEAR(fecha)',
        'mes' => 'MONTH(fecha)'
    ];

    protected $operatorMap = [];

    public function transform(Request $request)
    {
        $eloQuery = [];
    
        foreach ($this->safeParms as $parm => $operators) {
            $query = $request->query($parm);
    
            if (!isset($query)) {
                continue;
            }
    
            $column = $this->columnMap[$parm] ?? $parm;
    
            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    if (in_array($parm, ['anio', 'mes'])) {
                        $eloQuery[] = [\DB::raw($column), $this->operatorMap[$operator], $query[$operator]];
                    } else {
                        $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                    }
                }
            }
        }
    
        return $eloQuery;
    }
}