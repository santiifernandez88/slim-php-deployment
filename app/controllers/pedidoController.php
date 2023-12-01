<?php

include './models/pedido.php';
//include './models/ProductoPedido.php';
//include './controllers/ProductoPedidoController.php';


class PedidoController implements IApiUsable
{
    public function Insertar($request, $response, $args)
    {

        $parametros = $request->getParsedBody();

        if(isset($parametros['nombreCliente']) && isset($parametros['totalPrecio']) && isset($parametros['numeroMesa']))
        {
            $pedido = new Pedido();
            $pedido->id = self::GenerarId();
            $pedido->nombreCliente = $parametros['nombreCliente']; // aca esta igual y anda
            $pedido->totalPrecio = $parametros['totalPrecio'];
            $pedido->estado = "Pendiente";
            $pedido->tiempoEstimado = 0;
            $pedido->numeroMesa = $parametros['numeroMesa'];
            Pedido::InsertarPedido($pedido);
            $payload = json_encode(array("mensaje" => "Pedido creado con exito"));
        }
        else
        {
            $payload = json_encode(array("error" => "No se pudo crear el pedido"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::TraerTodos();

        $payload = json_encode($lista);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $pedido = Pedido::TraerUno($id);
        $payload = json_encode($pedido);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function Modificar($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $args['id'];
        $pedido = Pedido::TraerUno($id);

        if(isset($parametros['nombreCliente']) && isset($parametros['totalPrecio']) && isset($parametros['numeroMesa']))
        {
            $pedido->nombreCliente = $parametros['nombreCliente'];
            $pedido->totalPrecio = $parametros['totalPrecio'];
            $pedido->numeroMesa = $parametros['numeroMesa'];

            Pedido::ModificarPedido($pedido);
            $payload = json_encode(array("mensaje" => "Pedido modificado con exito."));
        }
        else
        {
            $payload = json_encode(array("error" => "No se pudo modificar el pedido."));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function Borrar($request, $response, $args)
    {
        $parametros = $rquest->getParsedBody();
        $id = $parametros['id'];
        $pedido = Pedido::TraerUno($id);
        $pedido->estado = "Cancelado";
        Pedido::BorrarPedido($pedido);
        $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarEstadoTiempo($request, $response, $args)
    {    
        $id = $args['id'];
        $pedido = Pedido::TraerUno($id);
        if($pedido != false)
        {
            $pedido->estado = ProductoPedidoController::EvaluarEstado($pedido->id);
            $pedido->tiempoEstimado = ProductoPedidoController::EvaluarTiempo($pedido->id);
    
            Pedido::ModificarPedidoEstadoTiempo($pedido);
            $payload = json_encode(array("mensaje" => "Pedido modificado con exito."));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No se puedo mofificar el pedido con exito."));
        }
        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


    public function GenerarId()
    {
        $id = "";
        $caracteres = "0123456789abcdefghijklmnopqrstuvwxyz";

        for($i = 0; $i < 5; $i++)
        {
            $id .= $caracteres[rand(0, strlen($caracteres)-1)];
        }

        return $id;
    }

    public function ValidarEstado($estado)
    {
        if($estado === "Preparacion" || $estado === "Cancelado" || $estado === "Entregado")
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