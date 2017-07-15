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
    $passwordPost = md5($allPostPutVars['password']);

    $sql = $this->db->prepare("SELECT * FROM users JOIN profiles ON users.id = profiles.id WHERE users.user = '" .$userPost. "' AND users.password = '" .$passwordPost. "' ");
    $sql->execute();
    $resultAll = $sql->fetchAll();

    $responseLogin = Array();

    if ( count($resultAll) == 1 ) {

      $result = $resultAll[0];
      // $path = $this->container->get('router')->pathFor('token');
      $path = $this->container->get('router')->pathFor('authorize');
      $path .= "?user_id=".$result['id'];

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
        // "scope" => "bookCreate2",
        "authorized" => "yes",
        "state" => "xyz"
      );

      $curlRequest = new CurlRequest();
      $curlRequest->addContextOption(CURLOPT_POSTFIELDS, json_encode($postFields));
      $curlRequest->addContextOption(CURLOPT_CUSTOMREQUEST, "POST");
      $curlRequest->addContextOption(CURLOPT_URL, URL_BASE.$path);
      $curlResponse = $curlRequest->sendCurlRequest();
// print_r($curlResponse['response']);
      if ( $curlResponse['success'] ) {

        $path = "/api/token";
        $postFields = array(
	      	"client_id" => "social",
	      	"client_secret" => "secret",
          "code" => $curlResponse['response']->code,
        	"grant_type" => "authorization_code"
        );

        $curlRequest = new CurlRequest();
        $curlRequest->addContextOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $curlRequest->addContextOption(CURLOPT_USERPWD, "social:secret");
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
          $userProfile->setAvatar( ($result['avatar'] == "") ? "" : AVATAR_URL_BASE.$result['avatar'] );

          $responseLogin['success'] = $curlResponse['success'];
          $responseLogin['token'] = $curlResponse['response'];
          $responseLogin['user'] = $userProfile;
          $newResponse = $response->withJson($responseLogin);

        } else {
          $responseLogin['success'] = false;
          $responseLogin['error_description'] = "Algo ha ido mal con el endpoint del token (2º PARTE)";
          $newResponse = $response->withJson($responseLogin)->withStatus(503, $reasonPhrase = 'Service Unavailable');
        }
      } else {
        $responseLogin['success'] = false;
        $responseLogin['error_description'] = "Algo ha ido mal con el endpoint del token";
        $newResponse = $response->withJson($responseLogin)->withStatus(500, $reasonPhrase = 'Server Error');
      }
    } else {
      $responseLogin['success'] = false;
      $responseLogin['error_description'] = "Usuario y/o contraseña erroneos";
      $newResponse = $response->withJson($responseLogin)->withStatus(401, $reasonPhrase = 'Unauthorized');
    }

    // return $response->withJson($responseLogin);
    return $newResponse;
  }

}
