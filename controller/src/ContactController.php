<?php

namespace Controller;

use \Interop\Container\ContainerInterface as ContainerInterface;
use Service\UserEntity;
use Service\UserProfileEntity;
use Service\CurlRequest;

class ContactController
{
  protected $container;
  protected $db;

  // constructor receives container instance
  public function __construct(ContainerInterface $container) {
    $this->container = $container;
    $this->db = $this->container->db;
  }

  public function getContacts($request, $response, $args) {
    $sql = $this->db->prepare("SELECT `contacts` FROM `contacts` WHERE id = ". $args['id']);
    $sql->execute();
    $result = $sql->fetch();
    $contactsRequest = json_decode($result["contacts"]);
    if ( $contactsRequest === null ) {
      $contactsRequest = array();
    }
    return $response->withJson($contactsRequest);
  }

  public function getRequest($request, $response, $args) {
    $sql = $this->db->prepare("SELECT `contactsRequest` FROM `contacts` WHERE id = ". $args['id']);
    $sql->execute();
    $result = $sql->fetch();
    $contactsRequest = json_decode($result["contactsRequest"]);
    if ( $contactsRequest === null ) {
      $contactsRequest = array();
    }
    return $response->withJson($contactsRequest);
  }

  public function getPetition($request, $response, $args) {
    $sql = $this->db->prepare("SELECT `contactsPetitions` FROM `contacts` WHERE id = ". $args['id']);
    $sql->execute();
    $result = $sql->fetch();
    $contactsRequest = json_decode($result["contactsPetitions"]);
    if ( $contactsRequest === null ) {
      $contactsRequest = array();
    }
    return $response->withJson($contactsRequest);
  }

  private function getContactsArrayById($table,$column,$id) {
    $sql = $this->db->prepare("SELECT `$column` FROM `$table` WHERE id = ". $id);
    $sql->execute();
    $result = $sql->fetch();
    $result = json_decode($result[$column]);
    if ( $result === null ) {
      $result = array();
    }
    return $result;
  }

  private function updateContactsArrayById($table,$column,$id,$contactsRequest,$userId) {
    $sql = $this->db->prepare("UPDATE `$table` SET `$column`=? WHERE id = ". $id);
    $isIdExist = false;
    for ($i=0; $i < count($contactsRequest); $i++) {
      if ( $contactsRequest[$i]->id == $userId ) {
        $isIdExist = true;
        break;
      }
    }
    if (!$isIdExist) {
      array_push($contactsRequest, array(
        "id" => $userId,
        "user" => $userId
      ) );
    }

    $contactsRequest = json_encode($contactsRequest);
    $sql->execute([
      $contactsRequest
    ]);
    return $sql;
  }

  private function deleteContactsArrayById($table,$column,$id,$contactsRequest,$userId) {
    $sql = $this->db->prepare("UPDATE `$table` SET `$column`=? WHERE id = ". $id);
    $isIdExist = false;
    for ($i=0; $i < count($contactsRequest); $i++) {
      if ( $contactsRequest[$i]->id == $userId ) {
        unset( $contactsRequest[$i] );
        $isIdExist = true;
        break;
      }
    }
    $contactsRequest = array_values($contactsRequest);
    $contactsRequest = json_encode($contactsRequest);
    $sql->execute([
      $contactsRequest
    ]);
    return $sql;
  }

  private function isValueInKeyListArray($array,$key,$value) {
    $isIdExist = false;
    for ($i=0; $i < count($array); $i++) {
      if ( $array[$i]->$key == $value ) {
        $isIdExist = true;
        break;
      }
    }
    return $isIdExist;
  }

  public function acceptContact($request, $response, $args) {
    $idUserGoingAccept = $args['id'];
    $allPostPutVars = $request->getParsedBody();
    $myIdUser = $allPostPutVars['id'];

    //  Check if user is going accept is in contactRequest column from contacts table
    $contactsRequest = $this->getContactsArrayById("contacts","contactsRequest",$myIdUser);
    $isIdExist = $this->isValueInKeyListArray($contactsRequest,id,$idUserGoingAccept);

    if ($isIdExist) {print_r("paso 2 ");

      // Checking if in other user is myIdUser in contactsPetitions column from contacts table
      $contactsPetitions = $this->getContactsArrayById("contacts","contactsPetitions",$idUserGoingAccept);
      $isIdExist = $this->isValueInKeyListArray($contactsPetitions,id,$myIdUser);

      if ($isIdExist) {

        $myContacts = $this->getContactsArrayById("contacts","contacts",$myIdUser);
        $hisContacts = $this->getContactsArrayById("contacts","contacts",$idUserGoingAccept);

        $myContactsRequest = $this->getContactsArrayById("contacts","contactsRequest",$myIdUser);
        $hisContactsPetitions = $this->getContactsArrayById("contacts","contactsPetitions",$idUserGoingAccept);

        // Update contacts column from contacts table
        try {
          $this->db->beginTransaction();

          $meSql = $this->updateContactsArrayById("contacts","contacts",$myIdUser,$myContacts,$idUserGoingAccept);
          $himSql = $this->updateContactsArrayById("contacts","contacts",$idUserGoingAccept,$hisContacts,$myIdUser);

          $himSql2 = $this->deleteContactsArrayById("contacts","contactsRequest",$myIdUser,$myContactsRequest,$idUserGoingAccept);
          $meSql2 = $this->deleteContactsArrayById("contacts","contactsPetitions",$idUserGoingAccept,$hisContactsPetitions,$myIdUser);

          if( $meSql->affected_rows >= 0 && $himSql->affected_rows >= 0 && $meSql2->affected_rows >= 0 && $himSql2->affected_rows >= 0 ){
            $responseUpdate['success'] = true;
            $responseUpdate['message'] = "Contacto agregado correctamente";
            $responseUpdate['contacts'] = $myContacts;
          } else {
            $responseUpdate['success'] = false;
            $responseUpdate['message'] = "Error al agregar el contacto";
          }
          $this->db->commit();
        } catch(PDOExecption $e) {
          $this->db->rollback();
          $responseUpdate['success'] = false;
          $responseUpdate['message'] = "Error al agregar el contacto: ".$e->getMessage();
        }

      } else {
        // Doesn't exit user in contactsPetitions column from contacts table
        // Deleting user fromcontactRequest column from contacts table
        // $sql = $this->db->prepare("SELECT contactsRequest FROM contacts WHERE id = ". $myIdUser);
        // $sql->execute();
        // $result = $sql->fetch();
        $responseUpdate['success'] = false;
        $responseUpdate['message'] = "Doesn't exit user in contactsPetitions column from contacts table";
      }

    } else{
      // Doesn't exit user in contactRequest column from contacts table
      $responseUpdate['success'] = false;
      $responseUpdate['message'] = "Doesn't exit user in contactRequest column from contacts table";
    }

    return $response->withJson($responseUpdate);
  }

  public function update($request, $response, $args) {
    $allPostPutVars = $request->getParsedBody();
    $idUserGoingPetition = $args['id'];
    $myIdUser = $allPostPutVars['id'];

    $sql = $this->db->prepare("SELECT users.id,users.user FROM users WHERE users.id = ". $idUserGoingPetition);
    $sql->execute();
    $result = $sql->fetch();

    $userRequest =  new UserEntity($result['id']);
    $userRequest->setUser($result['user']);

    $userPetition =  new UserEntity($myIdUser);
    $userPetition->setUser($allPostPutVars['user']);

    //Verificamos si ya esta en nuestros contactos y en nestra contactsPetitions
    $myContacts = $this->getContactsArrayById("contacts","contacts",$myIdUser);
    $isIdExist = $this->isValueInKeyListArray($myContacts,id,$idUserGoingPetition);
    $myContactsPetitions = $this->getContactsArrayById("contacts","contactsPetitions",$myIdUser);
    $isIdExist2 = $this->isValueInKeyListArray($myContactsPetitions,id,$idUserGoingPetition);
    if ( !$isIdExist && !$isIdExist2 ) {

      $contactsRequest = $this->getContactsArrayById("contacts","contactsPetitions",$myIdUser);
      $contactsPetition = $this->getContactsArrayById("contacts","contactsRequest",$userRequest->getId());

      try {
        $this->db->beginTransaction();

        $sql = $this->updateContactsArrayById("contacts","contactsPetitions",$myIdUser,$contactsRequest,$userRequest->getId());
        $sql2 = $this->updateContactsArrayById("contacts","contactsRequest",$userRequest->getId(),$contactsPetition,$userPetition->getId());

        if( $sql->affected_rows >= 0 && $sql2->affected_rows >= 0 ){
          $responseUpdate['success'] = true;
          $responseUpdate['message'] = "Petición realizada correctamente";
        } else {
          $responseUpdate['success'] = false;
          $responseUpdate['message'] = "Error al realizar la petición de contacto";
        }
        $this->db->commit();
      } catch(PDOExecption $e) {
        $this->db->rollback();
        $responseUpdate['success'] = false;
        $responseUpdate['message'] = "Error al actualizar el contacto: ".$e->getMessage();
      }

    } else {
      //Ya tengo al contacto
      $responseUpdate['success'] = false;
      if ($isIdExist2) {
      $responseUpdate['message'] = "El contacto está pendiente de aceptar la solicitud";
      }
      else {
        $responseUpdate['message'] = "El contacto ya está agregado";
      }
    }

    return $response->withJson($responseUpdate);
  }

  public function delete($request, $response, $args) {
    $allPostPutVars = $request->getParsedBody();
    $idUserGoingDelete = $args['id'];
    $myIdUser = $allPostPutVars['id'];

    //Verificamos si ya esta en nuestros contactos
    $myContacts = $this->getContactsArrayById("contacts","contacts",$myIdUser);
    $isIdExist = $this->isValueInKeyListArray($myContacts,id,$idUserGoingDelete);
    if ( $isIdExist ) {

      $hisContacts = $this->getContactsArrayById("contacts","contacts",$idUserGoingDelete);

      try {
        $this->db->beginTransaction();

        $sql = $this->deleteContactsArrayById("contacts","contacts",$myIdUser,$myContacts,$idUserGoingDelete);
        $sql2 = $this->deleteContactsArrayById("contacts","contacts",$idUserGoingDelete,$hisContacts,$myIdUser);

        if( $sql->affected_rows >= 0 && $sql2->affected_rows >= 0 ){
          $responseUpdate['success'] = true;
          $responseUpdate['message'] = "Se ha eliminado al contacto correctamente";
        } else {
          $responseUpdate['success'] = false;
          $responseUpdate['message'] = "Error al eliminar el contacto";
        }
        $this->db->commit();
      } catch(PDOExecption $e) {
        $this->db->rollback();
        $responseUpdate['success'] = false;
        $responseUpdate['message'] = "Error al eliminar el contacto: ".$e->getMessage();
      }

    } else {
      //Ya tengo al contacto
      $responseUpdate['success'] = false;
      $responseUpdate['message'] = "El contacto no está agregado";
    }

    return $response->withJson($responseUpdate);
  }

}
