<?php

use Slim\Psr7\Response;


class ArchivoController
{
    public static function CargarCSV($request, $response, $args)
    {

        $file = $request->getUploadedFiles();

        $file = $file['producto'];

        $pathTmp = $file->getStream()->getMetadata('uri');
        self::InsertarProductoCSV($pathTmp);

        $payload = json_encode(array("mensaje" => "Productos cargados con exito"));

        return $response
        ->withHeader('Content-Type', 'application/json');
            
    }

    public static function InsertarProductoCSV($pathTmp)
    {
        $flag = 0;

        $file = fopen($pathTmp, 'r');

        if($file != null)
        {
            while(($data = fgetcsv($file,1000,",")) != false)
            {
                if($flag == 0)
                {
                    $producto = new Producto();
                    $producto->id = $data[0];
                    $producto->nombre = $data[1];
                    $producto->precio = $data[2];
                    $producto->tipo = $data[3];
                    $producto->estado = $data[4];
                    
                    Producto::InsertarProducto($producto);
                }
                $flag = 0;
            }
        }

        fclose($file);

    }

    public static function DescargarCSV($request, $response, $args)
    {
        $productos = Producto::TraerTodos();

        $data = "id,nombre,precio,tipo,estado\n";

        foreach($productos as $producto)
        {
            $data = $data . $producto->id . ',' . $producto->nombre . ',' . $producto->precio . ',' . $producto->tipo . ',' . $producto->estado . "\n";
        }

        if($data != null)
        {
            $response = $response
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Disposition', 'attachment;filename=productos.csv')
            ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->withHeader('Cache-Control', 'post-check=0, pre-check=0')
            ->withHeader('Pragma', 'no-cache');
        }

        $response->getBody()->write($data);
        return $response
        ->withHeader('Content-Type', 'application/json');

    }


}







?>