<?php

include './models/ProductoPedido.php';


class ProductoPedidoController  implements IApiUsable
{
    public function Insertar($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        if(isset($parametros['idProducto']) && isset($parametros['idPedido']) && isset($parametros['cantidad']))
        {
            $productoPedido = new ProductoPedido();
            $productoPedido->idProducto = $parametros['idProducto'];
            $productoPedido->idPedido =  $parametros['idPedido'];
            $productoPedido->estado = "Pendiente";
            $productoPedido->tiempo = 0;
            $productoPedido->cantidad = $parametros['cantidad'];
            ProductoPedido::InsertarProductoPedido($productoPedido);
            $payload = json_encode(array("mensaje" => "ProductoPedido creado con exito."));
        }
        else
        {
            $payload = json_encode(array("error" => "No se pudo crear el ProductoPedido."));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = ProductoPedido::TraerTodos();

        $payload = json_encode($lista);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }

    public function TraerTodosIdPedido($request, $response, $args)
    {
        $id = $args['id'];
        $productoPedidosIdPedidos = ProductoPedido::TraerTodosIdPedido($id);

        $payload = json_encode($productoPedidosIdPedidos);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $productoPedido = ProductoPedido::TraerUno($id);
        $payload = json_encode($productoPedido);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function Modificar($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $args['id'];
        $productoPedido = ProductoPedido::TraerUno($id);

        if(isset($parametros['idProducto']) && isset($parametros['idPedido']) && isset($parametros['cantidad']) && isset($parametros['tiempo']) && isset($parametros['estado']))
        {
            $productoPedido->idProducto = $parametros['idProducto'];
            $productoPedido->idPedido = $parametros['idPedido'];
            $productoPedido->cantidad = $parametros['cantidad'];
            $productoPedido->tiempo = $parametros['tiempo'];
            $productoPedido->estado = $parametros['estado'];

            ProductoPedido::ModificarProductoPedido($productoPedido);
            $payload = json_encode(array("mensaje" => "Producto modificado con exito."));
        }
        else
        {
            $payload = json_encode(array("error" => "No se pudo modificar el producto."));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function Borrar($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $productoPedidoId = $parametros['id'];
        $productoPedido = ProductoPedido::TraerUno($productoPedidoId);
        $productoPedido->estado = "Cancelado";
        ProductoPedido::BorrarProductoPedido($productoPedido);

        $payload = json_encode(array("mensaje" => "ProductoPedido borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public static function EvaluarTiempo($idPedido)
    {
        $productoPedidos = ProductoPedido::TraerTodos();
        $acumuladorTiempo = 0;

        foreach($productoPedidos as $productos)
        {
            if($productos->idPedido == $idPedido && $productos->idEmpleado > 0 && $acumuladorTiempo < $productos->tiempo)
            {
                $acumuladorTiempo = $productos->tiempo;
            }
        }

        return $acumuladorTiempo;
    }

    public static function EvaluarEstado($idPedido)
    {       
        $productoPedidos = ProductoPedido::TraerTodos();
        
        foreach($productoPedidos as $productos)
        {
            if($productos->idPedido == $idPedido && $productos->idEmpleado > 0 && $productos->estado != "Cancelado")
            {
                $estadoPedido = "Preparacion";
                if($productos->estado == "Realizado")
                {
                    $estadoPedido = "Entregado";
                }
                break;
            }
        }

        return $estadoPedido;
    }

}


?>