<?php

namespace Service;

class StudentEntity implements \JsonSerializable {

  /**
  * [$id the id student]
  * @var [int]
  */
  protected $id;

  /**
   * [protected $surnameFirst]
   * @var [varchar(100)]
   */
  protected $surnameFirst;

  /**
   * [protected $surnameSecond]
   * @var [varchar(100)]
   */
  protected $surnameSecond;

  /**
   * [protected $name]
   * @var [varchar(100)]
   */
  protected $name;

  /**
   * Construct
   */
  public function __construct( $id = null ) {
    $this->id = $id;
  }

  public function getId() {
    return $this->id;
  }

  // public function setId($id) {
  //   $this->id = $id;
  // }

  public function getSurnameFirst() {
    return $this->surnameFirst;
  }

  public function setSurnameFirst($surnameFirst) {
    $this->surnameFirst = $surnameFirst;
  }

  public function getSurnameSecond() {
    return $this->surnameSecond;
  }

  public function setSurnameSecond($surnameSecond) {
    $this->surnameSecond = $surnameSecond;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function jsonSerialize() {
    return [
      'id' => $this->id,
      'surnameFirst' => $this->surnameFirst,
      'surnameSecond' => $this->surnameSecond,
      'name' => $this->name
    ];
  }

}
