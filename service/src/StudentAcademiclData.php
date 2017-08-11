<?php

namespace Service;

class StudentAcademicData extends StudentEntity {

  /**
  * [protected $estudiosReal]
  * @var [varchar(100)]
  */
  protected $estudiosReal;

  /**
   * [protected $centroDoc]
   * @var [varchar(100)]
   */
  protected $centroDoc;

  /**
   * [protected $titleYear]
   * @var [int]
   */
  protected $titleYear;

  /**
   * Construct
   */
  public function __construct( $id = null ) {
    $this->id = $id;
  }

  public function getEstudiosReal() {
    return $this->estudiosReal;
  }

  public function setEstudiosReal($estudiosReal) {
    $this->estudiosReal = $estudiosReal;
  }

  public function getCentroDoc() {
    return $this->centroDoc;
  }

  public function setCentroDoc($centroDoc) {
    $this->centroDoc = $centroDoc;
  }

  public function getTitleYear() {
    return $this->titleYear;
  }

  public function setTitleYear($titleYear) {
    $this->titleYear = $titleYear;
  }

  public function jsonSerialize() {
    return [
      'id' => $this->id,
      'surnameFirst' => $this->surnameFirst,
      'surnameSecond' => $this->surnameSecond,
      'name' => $this->name,
      'estudiosReal' => $this->estudiosReal,
      'centroDoc' => $this->centroDoc,
      'titleYear' => $this->titleYear
    ];
  }

}
