<?php

namespace Controller;

// use Interop\Container\ContainerInterface;
use \Interop\Container\ContainerInterface as ContainerInterface;
use Service\UserEntity;
use Service\UserProfileEntity;

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
      $user->setDateCreated($result[$i]['dateCreated']);
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
    $user->setDateCreated($result['dateCreated']);
    return $response->withJson($user);
  }

  public function findByUser($request, $response, $args) {
    // to access items in the container... $this->container->get('');
    // print_r("llega2");
    $sql = $this->db->prepare("SELECT * FROM `users` WHERE user = '". $args['user'] ."'");
    $sql->execute();
    $result = $sql->fetch();
    $user =  new UserEntity($result['id']);
    $user->setUser($result['user']);
    $user->setPassword($result['password']);
    $user->setDateCreated($result['dateCreated']);
    return $response->withJson($user);
    // return $user;
  }

  public function checkUser($request, $response, $args) {
    // to access items in the container... $this->container->get('');
    // print_r("llega2");
    $sql = $this->db->prepare("SELECT * FROM `users` WHERE user = '". $args['user'] ."'");
    $sql->execute();
    $result = $sql->fetch();
    $user =  new UserEntity($result['id']);
    $user->setUser($result['user']);
    $user->setPassword($result['password']);
    $user->setDateCreated($result['dateCreated']);
    // return $response->withJson($user);
    return $user;
  }

  public function create($request, $response, $args) {
    $allPostPutVars = $request->getParsedBody();
    $user = $this->checkUser($request,$response,$allPostPutVars);
    $responseCreate = array();
    if ( is_null( $user->getUser() ) ) {
      $user = "No existe usuario: " . $allPostPutVars['user'];
      $responseCreate['success'] = true;
    } else {
      $responseCreate['success'] = false;
      $responseCreate['message'] = "El usuario ya existe";
      $responseCreate['user'] = $user;
    }

    if ( $responseCreate['success'] ) {

      $sql = $this->db->prepare("INSERT INTO `users`(user, password, dateCreated) VALUES (?,?,?)");
      $userValue = $allPostPutVars['user'];
      $passwordValue = $allPostPutVars['password'];
      $dateCreatedValue = date("Y-m-d H:i:s");

      $sql2 = $this->db->prepare("INSERT INTO `profiles`(`id`, `name`, `surname`, `adress`, `city`, `country`, `zipCode`, `email`, `phone`, `biography`) VALUES (?,?,?,?,?,?,?,?,?,?)");
      $nameValue = $allPostPutVars['name'] ?: "";
      $surnameValue = $allPostPutVars['surname'] ?: "";
      $adressValue = $allPostPutVars['adress'] ?: "";
      $cityValue = $allPostPutVars['city'] ?: "";
      $countryValue = $allPostPutVars['country'] ?: "";
      $zipCodeValue = $allPostPutVars['zipCode'] ?: "";
      $emailValue = $allPostPutVars['email'] ?: "";
      $phoneValue = $allPostPutVars['phone'] ?: "";
      $biographyValue = $allPostPutVars['biography'] ?: "";

      $sql3 = $this->db->prepare("INSERT INTO `contacts`(id) VALUES (?)");

      try {
        $this->db->beginTransaction();
        // Save in Users table
        $sql->execute([
          $userValue,
          $passwordValue,
          $dateCreatedValue
        ]);
        $lastInsertId = $this->db->lastInsertId();
        $userNew =  new UserProfileEntity( $lastInsertId );
        // Save in Profile table
        $sql2->execute([
          $lastInsertId,
          $nameValue,
          $surnameValue,
          $adressValue,
          $cityValue,
          $countryValue,
          $zipCodeValue,
          $emailValue,
          $phoneValue,
          $biographyValue
        ]);
        // Save in Contacts table
        $sql3->execute([
          $lastInsertId
        ]);
        $userNew->setUser( $userValue );
        $userNew->setPassword( $passwordValue );
        $userNew->setDateCreated( $dateCreatedValue );
        $userNew->setName( $nameValue );
        $userNew->setSurname( $surnameValue );
        $userNew->setAdress( $adressValue );
        $userNew->setCity( $cityValue );
        $userNew->setZipCode( $zipCodeValue );
        $userNew->setEmail( $emailValue );
        $userNew->setPhone( $phoneValue );
        $userNew->setBiography( $biographyValue );

        $this->db->commit();
        $responseCreate['user'] = $userNew;
        $responseCreate['message'] = "El usuario se ha creado correctamente";
      } catch(PDOExecption $e) {
        $this->db->rollback();
        print "Error!: " . $e->getMessage() . "</br>";
      }
    }

    return $response->withJson($responseCreate);
    // print_r($allPostPutVars);
    // foreach($allPostPutVars as $key => $param){
    //    //POST or PUT parameters list
    // }

    // to access items in the container... $this->container->get('');
    // $sql = $this->db->prepare("SELECT * FROM `users` WHERE id = ". $args['id']);
    // $sql->execute();
    // $result = $sql->fetch();
    // $user =  new UserEntity($result['id']);
    // $user->setUser($result['user']);
    // $user->setPassword($result['password']);
    // return $response->withJson($user);
  }

  public function update($request, $response, $args) {
    $allPostPutVars = $request->getParsedBody();
    // to access items in the container... $this->container->get('');
    $sql = $this->db->prepare("UPDATE `users` SET `password`=? WHERE id = ". $args['id']);
    $passwordValue = $allPostPutVars['password'];
    // $responseUpdate['password'] = $passwordValue;
    // $responseUpdate['passwordLeng'] = strlen($passwordValue);
    if ( strlen($passwordValue) < 6 ) {
      $responseUpdate['success'] = false;
      $responseUpdate['message'] = "La contraeña tiene que tener al menos 6 caracteres";
    } else {
      try {
        $this->db->beginTransaction();
        // Update in Users table
        $sql->execute([
          $passwordValue
        ]);
        if( $sql->affected_rows >= 0 ){
          $responseUpdate['success'] = true;
          $responseUpdate['message'] = "Contraseña actualizada correctamente";
        } else {
          $responseUpdate['success'] = false;
          $responseUpdate['message'] = "Error al cambiar la contraseña";
        }
        $this->db->commit();
      } catch(PDOExecption $e) {
        $this->db->rollback();
        $responseUpdate['success'] = false;
        $responseUpdate['message'] = "Error al cambiar la contraseña";
        print "Error!: " . $e->getMessage() . "</br>";
      }
    }
    return $response->withJson($responseUpdate);
  }

  public function delete($request, $response, $args) {

    $sql = $this->db->prepare("SELECT * FROM `users` WHERE id = ". $args['id']);
    $sql->execute();
    $result = $sql->fetch();
    $user =  new UserEntity($result['id']);
    $user->setUser($result['user']);
    $user->setPassword($result['password']);
    $user->setDateCreated($result['dateCreated']);

    // return  $response->withJson($user);
    $responseDelete = array();
    if ( is_null( $user->getUser() ) ) {
      $responseDelete['message'] = "No existe usuario: " . $args['id'];
      $responseDelete['success'] = false;
      $responseDelete['user'] = $user;
    } else {
      $responseDelete['success'] = true;
      $responseDelete['user'] = $user;
    }

    if ( $responseDelete['success'] ) {
      $sql = $this->db->prepare("DELETE FROM `users` WHERE id = ?");
      try {
        $this->db->beginTransaction();
        // Update in Users table
        $sql->execute([
          $user->getId()
        ]);
        if( $sql->affected_rows >= 0 ){
          $responseDelete['success'] = true;
          $responseDelete['message'] = "Usuario eliminado correctamente";
        } else {
          $responseDelete['success'] = false;
          $responseDelete['message'] = "Error al eliminar usuario";
        }
        $this->db->commit();
      } catch(PDOExecption $e) {
        $this->db->rollback();
        $responseDelete['success'] = false;
        $responseDelete['message'] = "Error al eliminar usuario";
        // print "Error!: " . $e->getMessage() . "</br>";
      }
    }
    return $response->withJson($responseDelete);
  }

}
