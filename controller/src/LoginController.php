<?php

namespace Controller;

use \Interop\Container\ContainerInterface as ContainerInterface;
use Service\UserEntity;
use Service\UserProfileEntity;

class LoginController
{
  protected $container;
  protected $db;

  // constructor receives container instance
  public function __construct(ContainerInterface $container) {
    $this->container = $container;
    $this->db = $this->container->db;
  }

  public function login($request, $response, $args) {
    $allPostPutVars = $request->getParsedBody();
    $userPost = $allPostPutVars['user'];
    $passwordPost = $allPostPutVars['password'];
    // return $response->withJson($allPostPutVars);
    $sql = $this->db->prepare("SELECT * FROM users JOIN profiles ON users.id = profiles.id WHERE users.user = '" .$userPost. "'");
    $sql->execute();
    $result = $sql->fetch();

    $user =  new UserEntity($result['id']);
    $user->setUser($result['user']);
    $user->setPassword($result['password']);

    if ( !is_null( $user->getUser() ) && $passwordPost == $user->getPassword() ) {
      $userProfile =  new UserProfileEntity($result['id']);
      $userProfile->setUser($result['user']);
      $userProfile->setName($result['name']);
      $userProfile->setSurname($result['surname']);
      $userProfile->setAdress($result['adress']);
      $userProfile->setCity($result['city']);
      $userProfile->setCountry($result['country']);
      $userProfile->setZipCode($result['zipCode']);
      $userProfile->setEmail($result['email']);
      $userProfile->setPhone($result['phone']);
      $userProfile->setBiography($result['biography']);
    } else {
      $userProfile = new UserProfileEntity;
    }

    return $response->withJson($userProfile);
  }

}
