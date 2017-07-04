<?php

namespace Controller;

use \Interop\Container\ContainerInterface as ContainerInterface;

class Oauth2 {

  protected $container;
  protected $db;

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
    $this->db = $this->container->db;
  }

  public function extractTokenFromHeader($accessTokenHeader) {
    $accessTokenheaderArray = explode(" ", $accessTokenHeader);
    $accessToken = end($accessTokenheaderArray);
    return $accessToken;
  }

  public function userIdAccessToken( $accessToken ) {
    $sql = $this->db->prepare("SELECT `user_id` FROM `oauth_access_tokens` WHERE access_token = '" . $accessToken . "'" );
    $sql->execute();
    $result = $sql->fetch();
    return $result['user_id'];
  }

  public function userIdAccessTokenFromHeader( $accessTokenHeader ) {
    $accessToken = $this->extractTokenFromHeader($accessTokenHeader);
    return $this->userIdAccessToken($accessToken);
  }

}
