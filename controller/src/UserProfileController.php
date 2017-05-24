<?php

namespace Controller;

// use Interop\Container\ContainerInterface;
use \Interop\Container\ContainerInterface as ContainerInterface;
use Service\UserProfileEntity;

class UserProfileController
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
    $sql = $this->db->prepare("SELECT * FROM users JOIN profiles ON users.id = profiles.id");
    $sql->execute();
    $result = $sql->fetchAll();
    $users = [];
    for ($i=0; $i < count($result) ; $i++) {
      $user =  new UserProfileEntity($result[$i]['id']);
      $user->setUser($result[$i]['user']);
      $user->setEmail($result[$i]['email']);
      $user->setPhone($result[$i]['phone']);
      $users[] = $user;
    }
      // return "hola";
    return $response->withJson($users);
  }

  public function findById($request, $response, $args) {
    // to access items in the container... $this->container->get('');
    $sql = $this->db->prepare("SELECT * FROM users JOIN profiles ON users.id = profiles.id WHERE users.id = ". $args['id']);
    $sql->execute();
    $result = $sql->fetch();
    $user =  new UserProfileEntity($result['id']);
    $user->setUser($result['user']);
    $user->setEmail($result['email']);
    $user->setPhone($result['phone']);
    return $response->withJson($user);
  }

  public function contact($request, $response, $args) {
    // your code
    // to access items in the container... $this->container->get('');
    return $response;
  }
}
