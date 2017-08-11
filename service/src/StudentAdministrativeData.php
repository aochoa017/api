<?php

namespace Service;

class StudentPersonalData extends StudentEntity {

  /**
  * [protected $applicationDate]
  * @var [datetime]
  */
  protected $applicationDate;

  /**
   * [protected $codeExp]
   * @var [	varchar(10)]
   */
  protected $codeExp;

  /**
  * [$nExp the document number]
  * @var [int]
  */
  protected $nExp;

  /**
   * [protected $especiality]
   * @var [varchar(100)]
   */
  protected $especiality;

  /**
   * [protected $course]
   * @var [varchar(100)]
   */
  protected $course;

  /**
   * [protected $isRepeat]
   * @var [boolean]
   */
  protected $isRepeat;

  /**
   * [protected $session]
   * @var [varchar(50)]
   */
  protected $session;

  /**
   * [protected $howMeetUs]
   * @var [varchar(50)]
   */
  protected $howMeetUs;

  /**
   * [protected $pdfApplicationUrl]
   * @var [varchar(200)	]
   */
  protected $pdfApplicationUrl;

  /**
   * Construct
   */
  public function __construct( $id = null ) {
    $this->id = $id;
  }

  public function getApplicationDate() {
    return $this->applicationDate;
  }

  public function setApplicationDate($applicationDate) {
    $this->applicationDate = $applicationDate;
  }

  public function getCodeExp() {
    return $this->codeExp;
  }

  public function setCodeExp($codeExp) {
    $this->codeExp = $codeExp;
  }

  public function getNExp() {
    return $this->nExp;
  }

  public function setNExp($nExp) {
    $this->nExp = $nExp;
  }

  public function getEspeciality() {
    return $this->especiality;
  }

  public function setEspeciality($especiality) {
    $this->especiality = $especiality;
  }

  public function getCourse() {
    return $this->course;
  }

  public function setCourse($course) {
    $this->course = $course;
  }

  public function getIsRepeat() {
    return $this->isRepeat;
  }

  public function setIsRepeat($isRepeat) {
    $this->isRepeat = $isRepeat;
  }

  public function getSession() {
    return $this->session;
  }

  public function setSession($session) {
    $this->session = $session;
  }

  public function getHowMeetUs() {
    return $this->howMeetUs;
  }

  public function setHowMeetUs($howMeetUs) {
    $this->howMeetUs = $howMeetUs;
  }

  public function getPdfApplicationUrl() {
    return $this->pdfApplicationUrl;
  }

  public function setPdfApplicationUrl($pdfApplicationUrl) {
    $this->pdfApplicationUrl = $pdfApplicationUrl;
  }

  public function jsonSerialize() {
    return [
      'id' => $this->id,
      'surnameFirst' => $this->surnameFirst,
      'surnameSecond' => $this->surnameSecond,
      'name' => $this->name,
      'applicationDate' => $this->applicationDate,
      'codeExp' => $this->codeExp,
      'nExp' => $this->nExp,
      'especiality' => $this->especiality,
      'course' => $this->course,
      'isRepeat' => $this->isRepeat,
      'session' => $this->session,
      'howMeetUs' => $this->howMeetUs,
      'pdfApplicationUrl' => $this->pdfApplicationUrl
    ];
  }

}
