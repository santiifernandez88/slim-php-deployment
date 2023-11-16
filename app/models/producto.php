<?php

include "./db/AccesoDatos.php";

class Producto
{
    public $id;
    public $nombre;
    public $precio;
    public $tipo; 
    public $estado;

    public static function InsertarProducto($producto)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into productos (nombre,precio,tipo,estado)values(:nombre,:precio,:tipo,:estado)");
        $consulta->bindValue(':nombre', $producto->nombre);
        $consulta->bindValue(':precio', $producto->precio);
        $consulta->bindValue(':tipo', $producto->tipo);
        $consulta->bindValue(':estado', $producto->estado);
        $consulta->execute();
    }

    public static function TraerTodos()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function TraerUno($id)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function ModificarProducto($producto)
    {
        $objAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objAccesoDato->RetornarConsulta("UPDATE productos SET nombre = :nombre, precio = :precio, tipo = :tipo WHERE id = :id");
        $consulta->bindValue(':nombre', $producto->nombre);
        $consulta->bindValue(':precio', $producto->precio);
        $consulta->bindValue(':tipo', $producto->tipo);
        $consulta->bindValue(':id', $producto->id);
        $consulta->execute();
    }

    public static function BorrarProducto($producto)
    {
        $objAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDato->RetornarConsulta("UPDATE productos SET estado = :estado WHERE id = :id");
        $consulta->bindValue(':id', $producto->id);
        $consulta->bindValue(':estado', $producto->estado);
        $consulta->execute();
    }

}



?>