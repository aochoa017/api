<?php

namespace Controller;

use \Interop\Container\ContainerInterface as ContainerInterface;
use Service\StudentEntity;
// use Service\StudentProfileEntity;

class StudentController
{
  protected $container;
  protected $db;
  protected $oauth2;

  private $columnsDatatable;

  // constructor receives container instance
  public function __construct(ContainerInterface $container) {
    $this->container = $container;
    $this->db = $this->container->db;
    $this->oauth2 = new Oauth2($container);

    $this->columnsDatatable = array(
      "id" => "id",
      "apellido1" => "surnameFirst",
      "apellido2" => "surnameSecond",
      "nombre" => "name"
    );
  }

  /**
   * [getAllStudents description]
   * @param  [type] $request  [description]
   * @param  [type] $response [description]
   * @param  [type] $args     [description]
   * @return [type]           [description]
   */
  public function getAllStudents($request, $response, $args) {
    $sql = $this->db->prepare("SELECT * FROM `students`");
    $sql->execute();
    $result = $sql->fetchAll();
    $studentList = [];
    for ($i=0; $i < count($result) ; $i++) {
      $student = new StudentEntity($result[$i][ $this->columnsDatatable["id"] ]);
      $student->setSurnameFirst($result[$i][ $this->columnsDatatable["apellido1"] ]);
      $student->setSurnameSecond($result[$i][ $this->columnsDatatable["apellido2"] ]);
      $student->setName($result[$i][ $this->columnsDatatable["nombre"] ]);
      $studentList[] = $user;
    }
    return $response->withJson($studentList);
  }

  /**
   * [setStudent description]
   * @param [type] $request  [description]
   * @param [type] $response [description]
   * @param [type] $args     [description]
   */
  public function setStudent($request, $response, $args) {

    $allPostPutVars = $request->getParsedBody();
    $apellido1 = $allPostPutVars[ $this->columnsDatatable["apellido1"] ] ?: "";;
    $apellido2 = $allPostPutVars[ $this->columnsDatatable["apellido2"] ] ?: "";;
    $nombre = $allPostPutVars[ $this->columnsDatatable["nombre"] ] ?: "";;

    if( $nombre == "" || $apellido1 == "" ) {

      $responseCreate['success'] = false;
      $responseCreate['error_description'] = "El nombre y el primer apellido son obligatorios";
      $newResponse = $response->withJson($responseCreate)->withStatus(400, $reasonPhrase = 'Bad Request');

    } else {

      try {
        $this->db->beginTransaction();
        $sql = $this->db->prepare("INSERT INTO `students` (".$this->columnsDatatable["apellido1"].", ".$this->columnsDatatable["apellido2"].", ".$this->columnsDatatable["nombre"].") VALUES (?,?,?)");
        $sql->execute([
          $apellido1,
          $apellido2,
          $nombre
        ]);
        if( $sql->rowCount() >= 0 ){
          $responseCreate['success'] = true;
          $responseCreate['message'] = "Alumno nuevo creado correctamente.";
          $newResponse = $response->withJson($responseCreate);
        } else {
          $responseCreate['success'] = false;
          $responseCreate['error_description'] = "Error al registar nuevo alumno";
          $newResponse = $response->withJson($responseCreate)->withStatus(503, $reasonPhrase = 'Service Unavailable');
        }
        $this->db->commit();
      } catch(PDOExecption $e) {
        $this->db->rollback();
        $responseCreate['success'] = false;
        $responseCreate['error_description'] = "Error al registar nuevo alumno";
        $newResponse = $response->withJson($responseCreate)->withStatus(503, $reasonPhrase = 'Service Unavailable');
      }

    }

    return $newResponse;
  }

  /**
   * [getStudentById description]
   * @param  [type] $request  [description]
   * @param  [type] $response [description]
   * @param  [type] $args     [description]
   * @return [type]           [description]
   */
  public function getStudentById($request, $response, $args) {

    $sql = $this->db->prepare("SELECT * FROM `students` WHERE id = ". $args['id']);
    $sql->execute();

    if( $sql->rowCount() == 1 ){

      $result = $sql->fetch();

      $student = new StudentEntity($result[ $this->columnsDatatable["id"] ]);
      $student->setSurnameFirst($result[ $this->columnsDatatable["apellido1"] ]);
      $student->setSurnameSecond($result[ $this->columnsDatatable["apellido2"] ]);
      $student->setName($result[ $this->columnsDatatable["nombre"] ]);

      $responseCreate['student'] = $student;
      $newResponse = $response->withJson($responseCreate);

    } else {
      $responseCreate['success'] = false;
      $responseCreate['error_description'] = "Alumno no encontrado con el id = ". $args['id'];
      $newResponse = $response->withJson($responseCreate)->withStatus(400, $reasonPhrase = 'Bad Request');
    }

    return $newResponse;

  }

  /**
   * [putStudent description]
   * @param  [type] $request  [description]
   * @param  [type] $response [description]
   * @param  [type] $args     [description]
   * @return [type]           [description]
   */
  public function putStudent($request, $response, $args) {

    $allPostPutVars = $request->getParsedBody();
    $apellido1 = $allPostPutVars[ $this->columnsDatatable["apellido1"] ] ?: "";;
    $apellido2 = $allPostPutVars[ $this->columnsDatatable["apellido2"] ] ?: "";;
    $nombre = $allPostPutVars[ $this->columnsDatatable["nombre"] ] ?: "";;

    if( $nombre == "" || $apellido1 == "" ) {

      $responseCreate['success'] = false;
      $responseCreate['error_description'] = "El nombre y el primer apellido son obligatorios";
      $newResponse = $response->withJson($responseCreate)->withStatus(400, $reasonPhrase = 'Bad Request');

    } else {

      try {
        $this->db->beginTransaction();
        $sql = $this->db->prepare("UPDATE `students` SET `".$this->columnsDatatable["apellido1"]."`=?, `".$this->columnsDatatable["apellido2"]."`=?, `".$this->columnsDatatable["nombre"]."`=?  WHERE ".$this->columnsDatatable["id"]." = ". $args['id']);
        $sql->execute([
          $apellido1,
          $apellido2,
          $nombre
        ]);
        if( $sql->rowCount() >= 0 ){
          $student = new StudentEntity( $args['id'] );
          $student->setSurnameFirst( $apellido1 );
          $student->setSurnameSecond( $apellido2 );
          $student->setName( $nombre );

          $responseCreate['student'] = $student;

          $responseCreate['success'] = true;
          $responseCreate['message'] = "Alumno actualizado correctamente.";
          $newResponse = $response->withJson($responseCreate);
        } else {
          $responseCreate['success'] = false;
          $responseCreate['error_description'] = "Error al actualizar alumno";
          $newResponse = $response->withJson($responseCreate)->withStatus(503, $reasonPhrase = 'Service Unavailable');
        }
        $this->db->commit();
      } catch(PDOExecption $e) {
        $this->db->rollback();
        $responseCreate['success'] = false;
        $responseCreate['error_description'] = "Error al actualizar alumno";
        $newResponse = $response->withJson($responseCreate)->withStatus(503, $reasonPhrase = 'Service Unavailable');
      }

    }

    return $newResponse;

  }

  /**
   * [deleteStudent description]
   * @param  [type] $request  [description]
   * @param  [type] $response [description]
   * @param  [type] $args     [description]
   * @return [type]           [description]
   */
  public function deleteStudent($request, $response, $args) {

    $sql = $this->db->prepare("DELETE FROM `students` WHERE id = ?");
    try {
      $this->db->beginTransaction();
      $sql->execute([
        $args['id']
      ]);
      if( $sql->rowCount() >= 0 ){
        $responseDelete['success'] = true;
        $responseDelete['message'] = "Alumno eliminado correctamente";
        $newResponse = $response->withJson($responseDelete);
      } else {
        $responseDelete['success'] = false;
        $responseDelete['message'] = "Error al eliminar alumno";
        $newResponse = $response->withJson($responseDelete)->withStatus(400, $reasonPhrase = 'Bad Request');
      }
      $this->db->commit();
    } catch(PDOExecption $e) {
      $this->db->rollback();
      $responseDelete['success'] = false;
      $responseDelete['message'] = "Error al eliminar alumno";
      $newResponse = $response->withJson($responseDelete)->withStatus(503, $reasonPhrase = 'Service Unavailable');
    }

    return $newResponse;

  }

}
