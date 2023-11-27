<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AuthMiddleware
{
    public $autorizado;


    public function __construct($autorizado = "Socio") 
    {
        $this->autorizado = $autorizado;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        if(AutentificadorJWT::VerificarTipo($token, $this->autorizado)) 
        {
            $response = $handler->handle($request);
        } 
        else 
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Esta area no te corresponde'));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

     
}