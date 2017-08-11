<?php

namespace Service;

class StudentAcademicData extends StudentEntity {

  /**
  * [protected $isDniCopy]
  * @var [boolean]
  */
  protected $isDniCopy;

  /**
   * [protected $dniCopyUrl]
   * @var [varchar(100)]
   */
  protected $dniCopyUrl;

  /**
  * [protected $isPhoto]
  * @var [boolean]
  */
  protected $isPhoto;

  /**
   * [protected $photoUrl]
   * @var [varchar(100)]
   */
  protected $photoUrl;

  /**
  * [protected $isCertStudiesCopy]
  * @var [boolean]
  */
  protected $isCertStudiesCopy;

  /**
   * [protected $certStudiesCopyUrl]
   * @var [varchar(100)]
   */
  protected $certStudiesCopyUrl;

  /**
   * Construct
   */
  public function __construct( $id = null ) {
    $this->id = $id;
  }

  public function getIsDniCopy() {
    return $this->isDniCopy;
  }

  public function setIsDniCopy($isDniCopy) {
    $this->isDniCopy = $isDniCopy;
  }

  public function getDniCopyUrl() {
    return $this->dniCopyUrl;
  }

  public function setDniCopyUrl($dniCopyUrl) {
    $this->dniCopyUrl = $dniCopyUrl;
  }

  public function getIsPhoto() {
    return $this->isPhoto;
  }

  public function setIsPhoto($isPhoto) {
    $this->isPhoto = $isPhoto;
  }

  public function getPhotoUrl() {
    return $this->photoUrl;
  }

  public function setPhotoUrl($photoUrl) {
    $this->photoUrl = $photoUrl;
  }

  public function getIsCertStudiesCopy() {
    return $this->isCertStudiesCopy;
  }

  public function setIsCertStudiesCopy($isCertStudiesCopy) {
    $this->isCertStudiesCopy = $isCertStudiesCopy;
  }

  public function getCertStudiesCopyUrl() {
    return $this->certStudiesCopyUrl;
  }

  public function setCertStudiesCopyUrl($certStudiesCopyUrl) {
    $this->certStudiesCopyUrl = $certStudiesCopyUrl;
  }

  public function jsonSerialize() {
    return [
      'id' => $this->id,
      'surnameFirst' => $this->surnameFirst,
      'surnameSecond' => $this->surnameSecond,
      'name' => $this->name,
      'isDniCopy' => $this->isDniCopy,
      'dniCopyUrl' => $this->dniCopyUrl,
      'isPhoto' => $this->isPhoto,
      'photoUrl' => $this->photoUrl,
      'isCertStudiesCopy' => $this->isCertStudiesCopy,
      'certStudiesCopyUrl' => $this->certStudiesCopyUrl
    ];
  }

}
