<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;


require __DIR__ . '/../vendor/autoload.php';

include './controllers/productoController.php';
include './controllers/pedidoController.php';
include './controllers/encuestaController.php';
include './controllers/empleadoController.php';
include './controllers/mesaController.php';
include './controllers/ProductoPedidoController.php';
include './controllers/archivoController.php';
include './controllers/loginController.php';
//include './middlewares/loggerMiddleware.php';
include './middlewares/authMiddleware.php';


// Instantiate App
$app = AppFactory::create();

$app->setBasePath("/slim-php-deployment/app");

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->group('/login', function (RouteCollectorProxy $group) {
    $group->post('[/]', \LoginController::class . ':LogIn');
});


$app->group('/productos', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \ProductoController::class . ':TraerTodos');
    $group->get('/{id}', \ProductoController::class . ':TraerUno');
    $group->post('[/]', \ProductoController::class . ':Insertar');
    $group->put('/{id}', \ProductoController::class . ':Modificar');
    $group->delete('[/]', \ProductoController::class . ':Borrar');
});

$app->group('/empleados', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \EmpleadoController::class . ':TraerTodos');
    $group->get('/disponibles', \EmpleadoController::class . ':TraerEmpleadosDisponibles');
    $group->get('/{id}', \EmpleadoController::class . ':TraerUno');
    $group->post('[/]', \EmpleadoController::class . ':Insertar');
    $group->put('/{id}', \EmpleadoController::class . ':Modificar');
    $group->delete('[/]', \EmpleadoController::class . ':Borrar');
})->add(new AuthMiddleware());
//->add(new AuthMiddleware());LoggerMiddleware

$app->group('/pedidos', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \PedidoController::class . ':TraerTodos');
    $group->get('/{id}', \PedidoController::class . ':TraerUno');
    $group->post('[/]', \PedidoController::class . ':Insertar');
    $group->put('/{id}', \PedidoController::class . ':Modificar');
    $group->delete('[/]', \PedidoController::class . ':Borrar');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) 
{
    $group->put('/estadoTiempo/{id}', \PedidoController::class . ':ModificarEstadoTiempo');
});

$app->group('/mesas', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \MesaController::class . ':TraerTodos');
    $group->get('/{id}', \MesaController::class . ':TraerUno');
    $group->post('[/]', \MesaController::class . ':Insertar');
    $group->put('/{id}', \MesaController::class . ':Modificar');
    $group->delete('[/]', \MesaController::class . ':Borrar');
})->add(new AuthMiddleware("Mozo"));;

$app->group('/mesasUsada', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \MesaController::class . ':TraerMesaMasUsada');
})->add(new AuthMiddleware());;


$app->group('/encuestas', function (RouteCollectorProxy $group) 
{
    $group->get('/comentarios', \EncuestaController::class . ':TraerMejoresComentarios');
    $group->get('[/]', \EncuestaController::class . ':TraerTodos');
    $group->get('/{id}', \EncuestaController::class . ':TraerUno');
    $group->post('[/]', \EncuestaController::class . ':Insertar');
    $group->put('/{id}', \EncuestaController::class . ':Modificar');
    $group->delete('[/]', \EncuestaController::class . ':Borrar');
    
})->add(new AuthMiddleware());

$app->group('/productopedido', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \ProductoPedidoController::class . ':TraerTodos');
    $group->get('/{id}', \ProductoPedidoController::class . ':TraerUno');
    $group->get('/tipoProducto/{tipoProducto}', \ProductoPedidoController::class . ':TraerTipoProducto');
    $group->post('[/]', \ProductoPedidoController::class . ':Insertar');
    $group->put('/{id}', \ProductoPedidoController::class . ':Modificar');
    $group->delete('[/]', \ProductoPedidoController::class . ':Borrar');
});

$app->group('/csv', function (RouteCollectorProxy $group) 
{
    $group->post('/Cargar', \ArchivoController::class . ':CargarCSV');
    $group->post('/Descargar', \ArchivoController::class . ':DescargarCSV');
});


$app->run();

?>