<?php

class ProductoPedido
{
    public $id; 
    public $idProducto;
    public $idPedido;
    public $idEmpleado;
    public $cantidad;
    public $tiempo;
    public $estado;

    public static function InsertarProductoPedido($productoPedido)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into productopedido (idProducto,idPedido,cantidad,tiempo,estado)values(:idProducto,:idPedido,:cantidad,:tiempo,:estado)");
        $consulta->bindValue(':idProducto', $productoPedido->idProducto);
        $consulta->bindValue(':idPedido', $productoPedido->idPedido);
        $consulta->bindValue(':cantidad', $productoPedido->cantidad);
        $consulta->bindValue(':tiempo', $productoPedido->tiempo);
        $consulta->bindValue(':estado', $productoPedido->estado);
        $consulta->execute();
    }

    public static function TraerTodos()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM productopedido");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }

    public static function TraerTodosIdPedido($idPedido)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM productopedido WHERE id = :id");
        $consulta->bindValue(':id', $idPedido);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }

    public static function TraerUno($id)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM productopedido WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('ProductoPedido');
    }

    public static function ModificarProductoPedido($productoPedido)
    {
        $objAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objAccesoDato->RetornarConsulta("UPDATE productopedido SET idProducto = :idProducto, idPedido = :idPedido, cantidad = :cantidad, tiempo = :tiempo , estado = :estado WHERE id = :id");
        $consulta->bindValue(':idProducto', $productoPedido->idProducto);
        $consulta->bindValue(':idPedido', $productoPedido->idPedido);
        $consulta->bindValue(':cantidad', $productoPedido->cantidad);
        $consulta->bindValue(':tiempo', $productoPedido->tiempo);
        $consulta->bindValue(':estado', $productoPedido->estado);
        $consulta->bindValue(':id', $productoPedido->id);
        $consulta->execute();
    }

    public static function BorrarProductoPedido($productoPedido)
    {
        $objAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDato->RetornarConsulta("UPDATE productopedido SET estado = :estado WHERE id = :id");
        $consulta->bindValue(':id', $productoPedido->id);
        $consulta->bindValue(':estado', $productoPedido->estado);
        $consulta->execute();
    }

}

?>