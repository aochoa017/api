<?php

namespace Controller;

use \Interop\Container\ContainerInterface as ContainerInterface;
use Service\UserEntity;
use Service\UserProfileEntity;
use Service\CurlRequest;

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

    $sql = $this->db->prepare("SELECT * FROM users JOIN profiles ON users.id = profiles.id WHERE users.user = '" .$userPost. "' AND users.password = '" .$passwordPost. "' ");
    $sql->execute();
    $resultAll = $sql->fetchAll();
    $result = $resultAll[0];

    $responseLogin = Array();

    if ( count($resultAll) == 1 ) {

      // $path = $this->container->get('router')->pathFor('token');
      $path = $this->container->get('router')->pathFor('authorize');

      // $postFields = array(
      //   "user_id" => $result['id'],
      // 	"client_id" => "librarian",
      // 	"client_secret" => "secret",
      // 	"grant_type" => "client_credentials"
      // );
      $postFields = array(
        "client_id" => "social",
        "user_id" => $result['id'],
      	"client_secret" => "secret",
        "response_type" => "code",
        "authorized" => "yes",
        "state" => "xyz"
      );

      $curlRequest = new CurlRequest();
      $curlRequest->addContextOption(CURLOPT_POSTFIELDS, json_encode($postFields));
      $curlRequest->addContextOption(CURLOPT_CUSTOMREQUEST, "POST");
      $curlRequest->addContextOption(CURLOPT_URL, URL_BASE.$path);
      $curlResponse = $curlRequest->sendCurlRequest();

      if ( $curlResponse['success'] ) {

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
        $userProfile->setAvatar($result['avatar']);

        $responseLogin['success'] = $curlResponse['success'];
        $responseLogin['token'] = $curlResponse['response'];
        $responseLogin['user'] = $userProfile;
      } else {
        $responseLogin['success'] = false;
        $responseLogin['error'] = "Algo ha ido mal con el endpoint del token";
      }
    } else {
      $responseLogin['success'] = false;
      $responseLogin['error'] = "Usuario y/o contraseña erroneos";
    }

    return $response->withJson($responseLogin);
  }

}
