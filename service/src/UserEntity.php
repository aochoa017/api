<?php

namespace Service;

class UserEntity implements \JsonSerializable
{
  /**
  * [$id description]
  * @var [type]
  */
  protected $id;
  /**
  * [$user description]
  * @var [type]
  */
  protected $user;

  private $password;

  protected $dateCreated;

  protected $dateDeleted;

  /**
   * Construct
   */
  public function __construct( $id = null ) {
    $this->id = $id;
  }


public function getId()
{
  return $this->id;
}

public function getUser()
{
  return $this->user;
}

public function getPassword()
{
  return $this->password;
}

public function getDateCreated()
{
  return $this->dateCreated;
}

public function setId($id)
{
  $this->id = $id;
  return $this;
}

public function setUser($user)
{
  $this->user = $user;
  return $this;
}

public function setPassword($password)
{
  $this->password = $password;
  return $this;
}

public function setDateCreated($dateCreated)
{
  $this->dateCreated = $dateCreated;
  return $this;
}

public function jsonSerialize()
{
  return [
    'id' => $this->id,
    'user' => $this->user,
    'password' => $this->password,
    'dateCreated' => $this->dateCreated
  ];
}

}
