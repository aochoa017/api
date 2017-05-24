<?php

namespace Controller;

// use Interop\Container\ContainerInterface;
use \Interop\Container\ContainerInterface as ContainerInterface;
use Service\UserEntity;

class UserController
{
  protected $container;
  protected $db;

  // constructor receives container instance
  public function __construct(ContainerInterface $container) {
    $this->container = $container;
    $this->db = $this->container->db;
  }

  //  public function __invoke($request, $response, $args) {
  //       // your code
  //       // to access items in the container... $this->container->get('');
  //       return $response;
  //  }

  /**
  * Show all users
  * @param  [type] $request  [description]
  * @param  [type] $response [description]
  * @param  [type] $args     [description]
  * @return [type]           [description]
  */
  public function all($request, $response, $args) {
    // to access items in the container... $this->container->get('');
    $sql = $this->db->prepare("SELECT * FROM `users`");
    $sql->execute();
    $result = $sql->fetchAll();
    $users = [];
    for ($i=0; $i < count($result) ; $i++) {
      $user =  new UserEntity($result[$i]['id']);
      $user->setUser($result[$i]['user']);
      $user->setPassword($result[$i]['password']);
      $users[] = $user;
    }
    return $response->withJson($users);
  }

  public function findById($request, $response, $args) {
    // to access items in the container... $this->container->get('');
    $sql = $this->db->prepare("SELECT * FROM `users` WHERE id = ". $args['id']);
    $sql->execute();
    $result = $sql->fetch();
    $user =  new UserEntity($result['id']);
    $user->setUser($result['user']);
    $user->setPassword($result['password']);
    return $response->withJson($user);
  }

  public function contact($request, $response, $args) {
    // your code
    // to access items in the container... $this->container->get('');
    return $response;
  }
}
