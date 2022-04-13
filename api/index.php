<?php
require "../index.php";
use Src\PromoCode;
use Src\Response;
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// endpoints starting with `/promo` or `/promos` for GET shows all promos
// everything else results in a 404 Not Found
if ($uri[1] !== 'promo') {
  if($uri[1] !== 'promos'){
    $res = new Response('',404);
    return $res->get();
    exit();
  }
}

// endpoints starting with `/promos` for POST/PUT/DELETE results in a 404 Not Found
if ($uri[1] == 'promos' and isset($uri[2])) {
  $res = new Response('',404);
  return $res->get();
    exit();
}

// the promo ID is optional and must be a number
$promoId = null;
// IS Active is a flage to retirve just a valid promo codes 
$is_active = null;
// Ride to specify the type of request 
$ride = null;

if (isset($uri[2])) {
  $promoId = (int) $uri[2];
}
// Get only Active Promo Codes

if(isset($uri[2]) && $uri[2]=="active" ){
  $is_active = true;
  $promoId=null;
}
if(isset($uri[2]) && $uri[2]=="ride" ){
  $ride = true;
  $promoId=null;
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

// pass the request method and promo ID and process the HTTP request:
$controller = new PromoCode($dbConnection, $requestMethod, $promoId,$is_active,$ride);
$controller->processRequest();