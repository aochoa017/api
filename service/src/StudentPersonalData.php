<?php

namespace Service;

class StudentPersonalData extends StudentEntity {

  /**
   * [protected dni]
   * @var [varchar(15)]
   */
  protected $dni;

  /**
   * [protected passport]
   * @var [varchar(30)]
   */
  protected $passport;

  /**
   * [protected $adress]
   * @var [varchar(100)]
   */
  protected $adress;

  /**
   * [protected $city]
   * @var [varchar(50)]
   */
  protected $city;

  /**
   * [protected $province]
   * @var [varchar(50)]
   */
  protected $province;

  /**
   * [protected $cp]
   * @var [int]
   */
  protected $cp;

  /**
   * [protected $birthday]
   * @var [date]
   */
  protected $birthday;

  /**
   * [protected description]
   * @var [varchar(50)]
   */
  protected $birthplace;

  /**
   * [protected description]
   * @var [type]
   */
  protected $phone;

  /**
   * [protected varchar(100)]
   * @var [varchar(100)]
   */
  protected $email;

  /**
   * Construct
   */
  public function __construct( $id = null ) {
    $this->id = $id;
  }

  public function getDni() {
    return $this->dni;
  }

  public function setDni($dni) {
    $this->dni = $dni;
  }

  public function getPassport() {
    return $this->passport;
  }

  public function setPassport($passport) {
    $this->passport = $passport;
  }

  public function getAdress() {
    return $this->adress;
  }

  public function setAdress($adress) {
    $this->adress = $adress;
  }

  public function getCity() {
    return $this->city;
  }

  public function setCity($city) {
    $this->city = $city;
  }

  public function getProvince() {
    return $this->province;
  }

  public function setProvince($province) {
    $this->province = $province;
  }

  public function getCp() {
    return $this->cp;
  }

  public function setCp($cp) {
    $this->cp = $cp;
  }

  public function getBirthday() {
    return $this->birthday;
  }

  public function setBirthday($birthday) {
    $this->birthday = $birthday;
  }

  public function getBirthplace() {
    return $this->birthplace;
  }

  public function setBirthplace($birthplace) {
    $this->birthplace = $birthplace;
  }

  public function getPhone() {
    return $this->phone;
  }

  public function setPhone($phone) {
    $this->phone = $phone;
  }

  public function getEmail() {
    return $this->email;
  }

  public function setEmail($email) {
    $this->email = $email;
  }

  public function jsonSerialize() {
    return [
      'id' => $this->id,
      'surnameFirst' => $this->surnameFirst,
      'surnameSecond' => $this->surnameSecond,
      'name' => $this->name,
      'dni' => $this->dni,
      'passport' => $this->passport,
      'adress' => $this->adress,
      'city' => $this->city,
      'province' => $this->province,
      'cp' => $this->cp,
      'birthday' => $this->birthday,
      'birthplace' => $this->birthplace,
      'phone' => $this->phone,
      'email' => $this->email
    ];
  }

}
