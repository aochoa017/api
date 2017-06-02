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
      $user->setName($result[$i]['name']);
      $user->setSurname($result[$i]['surname']);
      $user->setAdress($result[$i]['adress']);
      $user->setCity($result[$i]['city']);
      $user->setCountry($result[$i]['country']);
      $user->setZipCode($result[$i]['zipCode']);
      $user->setEmail($result[$i]['email']);
      $user->setPhone($result[$i]['phone']);
      $user->setBiography($result[$i]['biography']);
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
    $user->setName($result['name']);
    $user->setSurname($result['surname']);
    $user->setAdress($result['adress']);
    $user->setCity($result['city']);
    $user->setCountry($result['country']);
    $user->setZipCode($result['zipCode']);
    $user->setEmail($result['email']);
    $user->setPhone($result['phone']);
    $user->setBiography($result['biography']);
    return $response->withJson($user);
  }

  public function update($request, $response, $args) {
    $allPostPutVars = $request->getParsedBody();

    $sql = $this->db->prepare("SELECT users.id,users.user FROM users JOIN profiles ON users.id = profiles.id WHERE users.id = ". $args['id']);
    $sql->execute();
    $result = $sql->fetch();

    $user =  new UserProfileEntity($result['id']);
    $user->setUser($result['user']);
    $user->setName($allPostPutVars['name']);
    $user->setSurname($allPostPutVars['surname']);
    $user->setAdress($allPostPutVars['adress']);
    $user->setCity($allPostPutVars['city']);
    $user->setCountry($allPostPutVars['country']);
    $user->setZipCode($allPostPutVars['zipCode']);
    $user->setEmail($allPostPutVars['email']);
    $user->setPhone($allPostPutVars['phone']);
    $user->setBiography($allPostPutVars['biography']);
    // return $response->withJson($user);

    $sql = $this->db->prepare("UPDATE `profiles` SET `name`=?, `surname`=?, `adress`=?, `city`=?, `country`=?, `zipCode`=?, `email`=?, `phone`=?, `biography`=?  WHERE id = ". $result['id']);
    $nameValue = $user->getName();
    $surnnameValue = $user->getSurname();
    $adressValue = $user->getAdress();
    $cityValue = $user->getCity();
    $countryValue = $user->getCountry();
    $zipCodeValue = $user->getZipCode();
    $emailValue = $user->getEmail();
    $phoneValue = $user->getPhone();
    $biographyValue = $user->getBiography();

    if ( false ) {
      $responseUpdate['success'] = false;
      $responseUpdate['message'] = "La contraeña tiene que tener al menos 6 caracteres";
    } else {
      try {
        $this->db->beginTransaction();
        // Update in Users table
        $sql->execute([
          $nameValue,
          $surnnameValue,
          $adressValue,
          $cityValue,
          $countryValue,
          $zipCodeValue,
          $emailValue,
          $phoneValue,
          $biographyValue
        ]);
        if( $sql->affected_rows >= 0 ){
          $responseUpdate['success'] = true;
          $responseUpdate['message'] = "Perfil actualizado correctamente";
          $responseUpdate['userProfile'] = $user;
        } else {
          $responseUpdate['success'] = false;
          $responseUpdate['message'] = "Error al actualizar el perfil";
        }
        $this->db->commit();
      } catch(PDOExecption $e) {
        $this->db->rollback();
        $responseUpdate['success'] = false;
        $responseUpdate['message'] = "Error al actualizar el perfil";
        print "Error!: " . $e->getMessage() . "</br>";
      }
    }
    return $response->withJson($responseUpdate);

  }


}
