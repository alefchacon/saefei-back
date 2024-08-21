<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

define('SIMPLELDAP_DOMAIN_CONTROLLER','148.226.12.10'); // este es el LDAP de la UV



class LDAPValidator {
   public static function validate (Request $request)
   {
        $ldapRdn  = $request->email; 
        $ldapPassword = $request->password;

        $ldapConnection = ldap_connect(SIMPLELDAP_DOMAIN_CONTROLLER);

        $validationStatus = 500;

        if (!$ldapConnection) {

            $validationStatus = 503;
            
        } else {
            ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, 0);
            $bindSuccessful = @ldap_bind($ldapConnection, $ldapRdn, $ldapPassword);

            $validationStatus = $bindSuccessful ? 200 : 401;
        }

        $messages = [
            200 => "Las credenciales son correctas",
            401 => "Las credenciales son incorrectas",
            500 => "Ocurrió un fallo inesperado",
            503 => "No se pudo conectar con los servidores de la Universidad Veracruzana",
        ];


        return [
            "status" => $validationStatus,
            "message" => $messages[$validationStatus],
        ];

   }
}