<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();

$container['logger'] = function($c) {
  $logger = new \Monolog\Logger('my_logger');
  $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
  $logger->pushHandler($file_handler);
  return $logger;
};

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
  $name = $args['name'];
  $this->logger->addInfo("name: $name");
  $response->getBody()->write("Hello, $name");

  return $response;
});

$app->post('/post-demo', function (Request $request, Response $response) {
  $data= implode($request->getParsedBody());
  $this->logger->addInfo($data);
  $response->getBody()->write($data);

  return $response;
});
$app->run();
