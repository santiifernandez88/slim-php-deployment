<?php

class Empleado
{
    public $usuario;
    public $contrasenia;
    public $id;
    public $rol;
    public $nombre;
    public $disponible;
    public $estado;

    public static function InsertarEmpleado($empleado)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into empleados (rol,nombre,disponible,usuario,contrasenia,estado)values(:rol,:nombre,:disponible,:usuario,:contrasenia,:estado)");
        $consulta->bindValue(':rol', $empleado->rol);
        $consulta->bindValue(':nombre', $empleado->nombre);
        $consulta->bindValue(':disponible', $empleado->disponible);
        $consulta->bindValue(':usuario', $empleado->usuario);
        $consulta->bindValue(':contrasenia', $empleado->contrasenia);
        $consulta->bindValue(':estado', $empleado->estado);
        $consulta->execute();
    }

    public static function TraerTodos()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM empleados");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }

    public static function TraerUno($id)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM empleados WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Empleado');
    }

    public static function modificarEmpleado($empleado)
    {
        $objAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objAccesoDato->RetornarConsulta("UPDATE empleados SET rol = :rol, nombre = :nombre, disponible = :disponible, estado = :estado WHERE id = :id");
        $consulta->bindValue(':rol', $empleado->rol);
        $consulta->bindValue(':nombre', $empleado->nombre);
        $consulta->bindValue(':disponible', $empleado->disponible);
        $consulta->bindValue(':estado', $empleado->estado);
        $consulta->bindValue(':id', $empleado->id);
        $consulta->execute();
    }

    public static function BorrarEmpleado($empleado)
    {
        $objAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDato->RetornarConsulta("UPDATE empleados SET estado = :estado WHERE id = :id");
        $consulta->bindValue(':id', $empleado->id);
        $consulta->bindValue(':estado', $empleado->estado);
        $consulta->execute();
    }

    public static function TraerEmpleadosDisponibles()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM empleados WHERE estado = 'Presente'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }

    public static function TraerUsuarioPorLogin($usuario, $contrasenia)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from empleados where usuario = :usuario AND contrasenia = :contrasenia");
        $consulta->bindValue(':usuario', $usuario);
        $consulta->bindValue(':contrasenia', $contrasenia);
        $consulta->execute();
        return $consulta->fetchObject('Empleado');
    }
}



?>