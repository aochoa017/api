<?php

namespace Service;

class ContactEntity implements \JsonSerializable
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
  protected $contacts;

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

public function getContacts()
{
  return $this->contacts;
}

public function setId($id)
{
  $this->id = $id;
  return $this;
}

public function setContact($contacts)
{
  $this->contacts = $contacts;
  return $this;
}

public function jsonSerialize()
{
  return [
    'id' => $this->id,
    'contacts' => $this->contacts,
  ];
}

}
