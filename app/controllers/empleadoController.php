<?php

include './models/empleado.php';

class EmpleadoController implements IApiUsable
{
    public function Insertar($request, $response, $args)
    {

        $parametros = $request->getParsedBody();

        if(isset($parametros['rol']) && isset($parametros['nombre']) && isset($parametros['disponible']) && isset($parametros['usuario']) && isset($parametros['contrasenia']) && isset($parametros['estado']) && self::ValidarDisponibilidad($parametros['disponible']))
        {
            $empleado = new Empleado();
            $empleado->rol = $parametros['rol'];
            $empleado->nombre = $parametros['nombre'];
            $empleado->disponible = $parametros['disponible'];
            $empleado->usuario = $parametros['usuario'];
            $empleado->contrasenia = $parametros['contrasenia'];
            $empleado->estado = $parametros['estado'];
            Empleado::InsertarEmpleado($empleado);
            $payload = json_encode(array("mensaje" => "Empleado creado con exito."));
        }
        else
        {
            $payload = json_encode(array("error" => "No se pudo crear el Empleado."));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Empleado::TraerTodos();

        $payload = json_encode($lista);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }

    public function TraerEmpleadosDisponibles($request, $response, $args)
    {
        $lista = Empleado::TraerEmpleadosDisponibles();

        $payload = json_encode($lista);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $empleado = Empleado::TraerUno($id);
        $payload = json_encode($empleado);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function Modificar($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $args['id'];
        $empleado = Empleado::TraerUno($id);

        var_dump($empleado);

        if(isset($parametros['rol']) && isset($parametros['nombre']) && isset($parametros['disponible']) && isset($parametros['usuario']) && isset($parametros['contrasenia']) && isset($parametros['estado']) && self::ValidarRol($parametros['rol']) && self::ValidarEstado($parametros['estado']) && self::ValidarDisponibilidad($parametros['disponible']))
        {
            $empleado->rol = $parametros['rol'];
            $empleado->nombre = $parametros['nombre'];
            $empleado->disponible = $parametros['disponible'];
            $empleado->usuario = $parametros['usuario'];
            $empleado->contrasenia = $parametros['contrasenia'];
            $empleado->estado = $parametros['estado'];

            Empleado::ModificarEmpleado($empleado);
            $payload = json_encode(array("mensaje" => "Empleado modificado con exito."));
        }
        else
        {
            $payload = json_encode(array("error" => "No se pudo modificar el empleado."));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function Borrar($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $empleadoId = $parametros['id'];
        $empleado = Empleado::TraerUno($empleadoId);
        $empleado->estado = "Inactivo";
        Empleado::BorrarEmpleado($empleado);

        $payload = json_encode(array("mensaje" => "Empleado borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ValidarRol($rol)
    {
        if($rol === "Socio" || $rol === "Cocinero" || $rol === "Bartender" || $rol === "Mozo" || $rol === "Cervecero")
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function ValidarEstado($estado)
    {
        if($estado === "Presente" || $estado === "Ausente")
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function ValidarDisponibilidad($disponible)
    {
        if($disponible === "True" || $disponible === "False")
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}


?>