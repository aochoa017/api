<?php

namespace Service;

final class UserProfileEntity extends UserEntity
{
  private $email;

  private $phone;


public function getEmail()
{
  return $this->email;
}

public function getPhone()
{
  return $this->phone;
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

public function jsonSerialize()
{
  return [
    'id' => $this->id,
    'user' => $this->user,
    'email' => $this->email,
    'phone' => $this->phone
  ];
}

}
