<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Illuminate\Http\Request;

use function Pest\Laravel\json;
use App\Http\Controllers\Auth\LDAPValidator;

class LDAPController extends Controller
{
    public function test(Request $request){
        $result = LDAPValidator::validate($request);

        return response()->json($result);
    }
}
