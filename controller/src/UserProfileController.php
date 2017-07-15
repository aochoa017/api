<?php

namespace Controller;

// use Interop\Container\ContainerInterface;
use \Interop\Container\ContainerInterface as ContainerInterface;
use Service\UserProfileEntity;

class UserProfileController
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
      $user->setAvatar(AVATAR_URL_BASE.$result[$i]['avatar']);
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
    $user->setAvatar( ($result['avatar'] == "") ? "" : AVATAR_URL_BASE.$result['avatar'] );
    return $response->withJson($user);
  }

  public function findByUser($request, $response, $args) {
    $sql = $this->db->prepare("SELECT * FROM users JOIN profiles ON users.id = profiles.id WHERE users.user = '". $args['user'] ."'");
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
    $user->setAvatar( ($result['avatar'] == "") ? "" : AVATAR_URL_BASE.$result['avatar']  );
    return $response->withJson($user);
    // return $user;
  }

  public function update($request, $response, $args) {

    $accessTokenHeader = $request->getHeaderLine('Authorization');
    $accessTokenId = $this->oauth2->userIdAccessTokenFromHeader($accessTokenHeader);

    if ( $args['id'] == $accessTokenId) {
      // return $response->withJson($accessTokenId);
      $allPostPutVars = $request->getParsedBody();

      $sql = $this->db->prepare("SELECT users.id,users.user,profiles.avatar FROM users JOIN profiles ON users.id = profiles.id WHERE users.id = ". $accessTokenId);
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
      $user->setAvatar($result['avatar']);
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
        $responseUpdate['error_description'] = "La contraeña tiene que tener al menos 6 caracteres";
        $newResponse = $response->withJson($responseUpdate)->withStatus(400, $reasonPhrase = 'Bad Request');
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
          if( $sql->rowCount() >= 0 ){
            $responseUpdate['success'] = true;
            $responseUpdate['message'] = "Perfil actualizado correctamente";
            $responseUpdate['userProfile'] = $user;
            $newResponse = $response->withJson($responseUpdate);
          } else {
            $responseUpdate['success'] = false;
            $responseUpdate['error_description'] = "Error al actualizar el perfil";
            $newResponse = $response->withJson($responseUpdate)->withStatus(503, $reasonPhrase = 'Service Unavailable');
          }
          $this->db->commit();
        } catch(PDOExecption $e) {
          $this->db->rollback();
          $responseUpdate['success'] = false;
          $responseUpdate['error_description'] = "Error al actualizar el perfil";
          $newResponse = $response->withJson($responseUpdate)->withStatus(503, $reasonPhrase = 'Service Unavailable');
          // print "Error!: " . $e->getMessage() . "</br>";
        }
      }

    } else {
      //No coincide el user_id asociado al token con el id recibido en la peticion
      $responseUpdate['success'] = false;
      $responseUpdate['error_description'] = "No coincide el user id con el token enviado";
      $newResponse = $response->withJson($responseUpdate)->withStatus(401, $reasonPhrase = 'Unauthorized');
    }

    return $newResponse;

  }

  public function avatar($request, $response, $args) {

    $responseUpload = [];
    // $log[] = $_SERVER;

    $file = $request->getUploadedFiles();
    $fileAvatar = $file['avatar'];
    /*
      getUploadedFiles METHODS
        ->getStream()
        ->moveTo($targetPath)
        ->getSize()
        ->getError()
        ->getClientFilename()
        ->getClientMediaType()
     */

    if ( $fileAvatar->getError() ) {
      $responseUpload['success'] = false;
      $responseUpload["message"] = "Error en el fichero: " . $fileAvatar->getError();
      return $response->withJson($responseUpload);
    }

    $sql = $this->db->prepare("SELECT users.id, users.user FROM users WHERE users.id = ". $args['id']);
    $sql->execute();
    $result = $sql->fetch();
    if ( $result['user'] == "" || is_null($result['user']) ) {
      $responseUpload['success'] = false;
      $responseUpload["message"] = "El id del usuario no se encuentra";
      return $response->withJson($responseUpload);
    }

    $target_dir = "assets/user/avatar/";
    $newFileAvatarName = $result['id'] . "-" . $result['user'] . "-" . date("YmdHis");
    $target_file = $target_dir . $newFileAvatarName;
    $uploadOk = 1;
    $maxSizeFile = 3145728; // 3 Mb

    if (empty($fileAvatar)) {
        throw new Exception('Expected an avatar file image');
    }

    // Check file size
    if ($fileAvatar->getSize() > $maxSizeFile) {
      $responseUpload['success'] = false;
      $responseUpload["message"] = "Sorry, your file is too large.";
      $uploadOk = 0;
    }

    // Allow certain file formats
    $mime_types = array(
      'image/jpeg' => 'jpg',
      'image/png' => 'png',
      'image/gif' => 'gif'
    );
    if ( array_key_exists($fileAvatar->getClientMediaType(), $mime_types) ) {
      $newFileAvatarNameComplete = $newFileAvatarName . "." . $mime_types[$fileAvatar->getClientMediaType()];
      $target_file .= "." . $mime_types[$fileAvatar->getClientMediaType()];
    } else {
      $responseUpload['success'] = false;
      $responseUpload["message"] .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk != 0) {
      $sql = $this->db->prepare("SELECT `avatar` FROM profiles WHERE id = ". $result['id']);
      $sql->execute();
      $result2 = $sql->fetch();
      if ( $result2['avatar'] != "" && !unlink( $target_dir . $result2['avatar'] ) ) {
        $responseUpload['success'] = false;
        $responseUpload["message"] = "Ha habido un problema al eliminar el antiguo avatar.";
      } else {
        try {
          $this->db->beginTransaction();
          $sql = $this->db->prepare("UPDATE `profiles` SET `avatar`=? WHERE id = ". $result['id']);
          $sql->execute([
            $newFileAvatarNameComplete
          ]);
          if( $sql->rowCount() >= 0 ){
            $fileAvatar->moveTo($target_file);
            $responseUpload['success'] = true;
            $responseUpload['fileName'] = $newFileAvatarNameComplete;
            $responseUpload['message'] = "Avatar nuevo subido correctamente";
          } else {
            $responseUpload['success'] = false;
            $responseUpload['message'] = "Error al subir avatar nuevo";
          }
          $this->db->commit();
        } catch(PDOExecption $e) {
          $this->db->rollback();
          $responseUpload['success'] = false;
          $responseUpload['message'] = "Error al subir el avatar nuevo";
          // print "Error!: " . $e->getMessage() . "</br>";
        }
      }
    }

    return $response->withJson($responseUpload);

  }


}
