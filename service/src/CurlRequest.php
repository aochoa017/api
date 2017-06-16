<?php

namespace Service;

class CurlRequest{

  private $postFields = array(
  	"client_id" => "librarian",
  	"client_secret" => "secret",
  	"grant_type" => "client_credentials"
  );

  protected $context_options = array(
    CURLOPT_PORT => PORT,
    // CURLOPT_URL => "http://localhost:8888/api/token",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    // CURLOPT_CUSTOMREQUEST => "POST",
    // CURLOPT_POSTFIELDS => "{\n\t\"client_id\": \"librarian\",\n\t\"client_secret\": \"secret\",\n\t\"grant_type\": \"client_credentials\"\n}",
    CURLOPT_HTTPHEADER => array(
      "cache-control: no-cache",
      "content-type: application/json"
    ),
  );

  /**
   * Construct
   */
  public function __construct() {
  }

  public function addContextOption($key, $value) {
    $this->context_options[$key] = $value;
  }

  public function sendCurlRequest() {
    // $this->context_options[CURLOPT_POSTFIELDS] = json_encode($this->postFields);
    // return $this->context_options[CURLOPT_POSTFIELDS];
    $curl = curl_init();
    curl_setopt_array($curl, $this->context_options);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
      $curlResponse['success'] = false;
      $curlResponse['error'] = $err;
      // echo "cURL Error #:" . $err;
    } else {
      $curlResponse['success'] = true;
      $curlResponse['response'] = json_decode($response);
      // echo $response;
    }

    return $curlResponse;
  }

}
/*
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_PORT => "8888",
  CURLOPT_URL => "http://localhost:8888/api/token",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\n\t\"client_id\": \"librarian\",\n\t\"client_secret\": \"secret\",\n\t\"grant_type\": \"client_credentials\"\n}",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: c1d25821-3193-7be5-84ed-2c7bab9fe997"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}*/
