<?php

namespace Controller;

use \Interop\Container\ContainerInterface as ContainerInterface;
use Service\UserEntity;
use Service\UserProfileEntity;

class DatabaseController
{
  protected $container;
  protected $db;
  protected $oauth2;

  // constructor receives container instance
  public function __construct(ContainerInterface $container) {
    $this->container = $container;
    $this->db = $this->container->db;
    $this->oauth2 = new Oauth2($container);
  }

  /**
  * Show all users
  * @param  [type] $request  [description]
  * @param  [type] $response [description]
  * @param  [type] $args     [description]
  * @return [type]           [description]
  */

  public function delete($request, $response, $args) {

    if ($args['type'] == "tokens") {

      $dateTime = date("Y-m-d H:i:s");

      $tables = array("oauth_access_tokens", "oauth_authorization_codes", "oauth_refresh_tokens");

      for ($i=0; $i < count($tables); $i++) {

        $sql = $this->db->prepare( "DELETE FROM `".$tables[$i]."` WHERE `expires` < '".$dateTime."'" );

        try {
          $this->db->beginTransaction();
          // Update in Users table
          $sql->execute();
          if( $sql->rowCount() >= 0 ){
            $responseDelete[$tables[$i]]['success'] = true;
          } else {
            $responseDelete[$tables[$i]]['success'] = false;
          }
          $this->db->commit();
        } catch(PDOExecption $e) {
          $this->db->rollback();
          $responseDelete[$tables[$i]]['success'] = false;
        }

      }

      return $response->withJson($responseDelete);
    }
  }

}
