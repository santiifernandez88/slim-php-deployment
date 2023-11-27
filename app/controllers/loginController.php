<?php

include "./utils/auntentificadorJWT.php";

class LoginController
{
    public function LogIn($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        if(isset($parametros['usuario']) && isset($parametros['contrasenia']))
        {
            $usuario = $parametros['usuario'];
            $contrasenia = $parametros['contrasenia'];
            $empleado = Empleado::TraerUsuarioPorLogin($usuario, $contrasenia);
    
            if($usuario)
            { 
                $datos = array('id' => $empleado->id, 'rol'=> $empleado->rol);
                $token = AutentificadorJWT::CrearToken($datos);
                $payload = json_encode(array('jwt' => $token));
            } 
            else
            {
                $payload = json_encode(array('error' => 'Usuario o contraseña incorrectos'));
            }
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}



?>