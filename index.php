<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require 'vendor/autoload.php';
require 'config/constans.php';

use Chadicus\Books\FileRepository;
use Chadicus\Slim\OAuth2\Routes;
use Chadicus\Slim\OAuth2\Middleware;
use Slim\Http;
use Slim\Views;
use OAuth2\Storage;
use OAuth2\GrantType;

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

$storage = new Storage\Pdo($container['db']);

$server = new OAuth2\Server(
    $storage,
    [
      'access_lifetime' => 86400,// 900 = 15 minutos
    ],
    [
      new GrantType\ClientCredentials($storage),
      new GrantType\AuthorizationCode($storage),
      new GrantType\RefreshToken($storage, array(
        'always_issue_new_refresh_token' => true
      )),
    ]
);

$app->post(Routes\Token::ROUTE, new Routes\Token($server))->setName('token');
$app->post(Controller\Authorize::ROUTE, new Controller\Authorize($server,null))->setName('authorize');
$authorization = new Middleware\Authorization($server, $app->getContainer());

$app->get('/users', Controller\UserController::class . ':all')->setName('users')->add($authorization);
$app->get('/user/{id}', Controller\UserController::class . ':findById');
$app->get('/user/find/{user}', Controller\UserController::class . ':findByUser')->setName('findByUser');
$app->post('/user', Controller\UserController::class . ':create');
$app->put('/user/{id}', Controller\UserController::class . ':update');
$app->delete('/user/{id}', Controller\UserController::class . ':delete');

$app->get('/profiles', Controller\UserProfileController::class . ':all')->setName('profiles')->add($authorization);
$app->get('/profile/{id}', Controller\UserProfileController::class . ':findById');
$app->get('/profile/find/{user}', Controller\UserProfileController::class . ':findByUser')->setName('findByUser');
$app->put('/profile/{id}', Controller\UserProfileController::class . ':update')->add($authorization);
$app->post('/profile/avatar/{id}', Controller\UserProfileController::class . ':avatar');

$app->post('/login', Controller\LoginController::class . ':login');

$app->get('/contacts/{id}', Controller\ContactController::class . ':getContacts');
$app->get('/contacts/requests/{id}', Controller\ContactController::class . ':getRequest');
$app->get('/contacts/petitions/{id}', Controller\ContactController::class . ':getPetition');
$app->put('/contacts/{id}', Controller\ContactController::class . ':update');
$app->post('/contacts/accept/{id}', Controller\ContactController::class . ':acceptContact');
$app->delete('/contacts/{id}', Controller\ContactController::class . ':delete');

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
