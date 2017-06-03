<?php

require 'vendor/autoload.php';

// use User\Service\UserEntity;


$config = require_once('config/database.php');
$app = new Slim\App($config);

$container = $app->getContainer();
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['database'],
        $db['username'], $db['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

// $container['UserController'] = function($c) {
//     // $view = $c->get("view"); // retrieve the 'view' from the container
//     return new UserController($c);
// };

// $app = new Slim\App();
$app->get('/users', Controller\UserController::class . ':all')->setName('users');
$app->get('/user/{id}', Controller\UserController::class . ':findById');
$app->get('/user/find/{user}', Controller\UserController::class . ':findByUser')->setName('findByUser');
$app->post('/user', Controller\UserController::class . ':create');
$app->put('/user/{id}', Controller\UserController::class . ':update');

$app->get('/profiles', Controller\UserProfileController::class . ':all');
$app->get('/profile/{id}', Controller\UserProfileController::class . ':findById');
$app->get('/profile/find/{user}', Controller\UserProfileController::class . ':findByUser')->setName('findByUser');
$app->put('/profile/{id}', Controller\UserProfileController::class . ':update');


$app->get('/users1', function ($request, $response, $args) {
  $sql = $this->db->prepare("SELECT * FROM `users` WHERE id = 1");
  $sql->execute();
  $result = array();
  while ($row = $sql->fetch(\PDO::FETCH_ASSOC)) {
    $result[] = array($row['id'], $row['user']);
  }
  return $response->withJson($result);
  /*
  // Use app HTTP cookie service
    // $this->get('cookies')->set('name', [
    //     'value' => $args['name'],
    //     'expires' => '7 days'
    // ]);

    // return $response->write("Hello, " . $args['name']);
    $user = new User\Service\UserEntity(777, $args['name'], "1234Pasword");
    // $user = new Service\UserEntity(999, $args['name'], "1234Pasword");
    return $response->withJson($user);
*/
});

$app->map(['GET', 'POST'], '/books', function ($request, $response, $args) {
  $writeArgs = "";
  foreach ($args as $key => $value) {
    $writeArgs .= $key.": ".$value."</br>";
  }
    return $response->write("Hello, BOOKS \n".$args['param1']);
});

$app->post('/postbooks', function ($request, $response, $args) {
  //GET
  // $allGetVars = $request->getQueryParams();
  /*foreach($allGetVars as $key => $param){
     //GET parameters list
  }*/

  //POST or PUT
  $allPostPutVars = $request->getParsedBody();
  foreach($allPostPutVars as $key => $param){
     //POST or PUT parameters list
  }
    return $response->write($allGetVars['param1']);
});

$app->run();
