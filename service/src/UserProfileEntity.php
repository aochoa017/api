<?php

namespace Service;

final class UserProfileEntity extends UserEntity
{
  private $name;

  private $surname;

  private $adress;

  private $city;

  private $country;

  private $zipCode;

  private $email;

  private $phone;

  private $biography;

  private $avatar;


  /*
  * Getters
  */
  public function getName()
  {
    return $this->name;
  }

  public function getSurname()
  {
    return $this->surname;
  }

  public function getAdress()
  {
    return $this->adress;
  }

  public function getCity()
  {
    return $this->city;
  }

  public function getCountry()
  {
    return $this->country;
  }

  public function getZipCode()
  {
    return $this->zipCode;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function getPhone()
  {
    return $this->phone;
  }

  public function getBiography()
  {
    return $this->biography;
  }

  public function getAvatar()
  {
    return $this->avatar;
  }

  /*
  * Setters
  */

  public function setName($name)
  {
    $this->name = $name;
    return $this;
  }

  public function setSurname($surname)
  {
    $this->surname = $surname;
    return $this;
  }

  public function setAdress($adress)
  {
    $this->adress = $adress;
    return $this;
  }

  public function setCity($city)
  {
    $this->city = $city;
    return $this;
  }

  public function setCountry($country)
  {
    $this->country = $country;
    return $this;
  }

  public function setZipCode($zipCode)
  {
    $this->zipCode = $zipCode;
    return $this;
  }

  public function setEmail($email)
  {
    $this->email = $email;
    return $this;
  }

  public function setPhone($phone)
  {
    $this->phone = $phone;
    return $this;
  }

  public function setBiography($biography)
  {
    $this->biography = $biography;
    return $this;
  }

  public function setAvatar($avatar)
  {
    $this->avatar = $avatar;
    return $this;
  }

  public function jsonSerialize()
  {
    return [
      'id' => $this->id,
      'user' => $this->user,
      'name' => $this->name,
      'surname' => $this->surname,
      'adress' => $this->adress,
      'city' => $this->city,
      'country' => $this->country,
      'zipCode' => $this->zipCode,
      'email' => $this->email,
      'phone' => $this->phone,
      'biography' => $this->biography,
      'avatar' => $this->avatar
    ];
  }

}
