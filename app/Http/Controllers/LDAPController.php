<?php

namespace App\Http\Controllers;

use App\Models\Espacio;
use App\Models\User;
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
    public function testAdmin(Request $request){
        //$espacio = Espacio::find($request->input("idEspacio"));
        $usuario = User::find($request->input("idUsuario"));
        
        //return response()->json($usuario->administradores());
        return response()->json($usuario->isAdministratorOf($request->input("idEspacio")));
        
    }
}
