<?php
namespace Src;

use Src\Response;
use Src\PromoCodeTypes;

class PromoCode {
  private $db;
  private $requestMethod;
  private $promoId;
  private $isActive;
  private $ride;


  public function __construct($db, $requestMethod, $promoId,$isActive,$ride)
  {
    $this->db = $db;
    $this->requestMethod = $requestMethod;
    $this->promoId = $promoId;
    $this->isActive = $isActive;
    $this->ride = $ride;
  }

  public function processRequest()
  {
    switch ($this->requestMethod) {
      case 'GET':
        if ($this->promoId) {
          $response = $this->getPromo($this->promoId);
        }else if ($this->isActive) {
          $response = $this->getActivePromos();
        }
         else {
          $response = $this->getAllPromos();
        };
        break;
      case 'POST':
        if(!$this->ride)
        $response = $this->createPromo();

        $response =  $this->promoApplicability();
        break;
      case 'PUT':
        $response = $this->updatePromo($this->promoId);
        break;
      case 'DELETE':
        $response = $this->deletePromo($this->promoId);
        break;
      default:
        $response = $this->notFoundResponse();
        break;
    }
   
    
  }

  private function getAllPromos()
  {
    $query = "
      SELECT
       *
      FROM
      promo_codes;
    ";

    try {
      $statement = $this->db->query($query);
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
    $r = new Response($result,200);
    return $r->get();
    
  }


  private function getActivePromos()
  {
    $query = "
      SELECT
       *
      FROM
      promo_codes;
      where is_active = 1
    ";

    try {
      $statement = $this->db->query($query);
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
    $r = new Response($result,200);
    return $r->get();
    
  }

  private function getPromo($id)
  {
    $result = $this->find($id);
    if (!$result) {

      return $this->notFoundResponse();
    }
    return $result;
  }

  private function createPromo()
  {
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
    if (! $this->validatePromo($input)) {
      return $this->unprocessableEntityResponse();
    }

    $query = "
      INSERT INTO promo_codes
        (type, amount,is_active,  code, longitude, latitude, radius, name, details, exp_date)
      VALUES
        (:type,:amount,:is_active, :code, :longitude, :latitude, :radius, :name, :details, :exp_date);
    ";
    $code =substr(md5(uniqid(rand(), true)),0,10) ;
    try {
      $statement = $this->db->prepare($query);
      $statement->execute(array(
        'type' => $input['type'],
        'amount'  => $input['amount'],
        'is_active' => 1,
        'longitude'  => $input['longitude'],
        'latitude'  => $input['latitude'],
        'radius'  => $input['radius'],
        'details'  => $input['details'],
        'exp_date'  => $input['exp_date'],
        'name'  => $input['name'],
        'code'=>$code
      ));
      $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
    $response = new Response(['Code'=>$code],201);
    $response->get();
  }

  private function updatePromo($id)
  {
    $result = $this->find($id,true);

    if (! $result) {
      return $this->notFoundResponse();
    }

    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
    if (! $this->validatePromo($input)) {
      return $this->unprocessableEntityResponse();
    }

    $query = "
      UPDATE promo_codes SET 
      type = :type,
      amount= :amount,
      is_active= :is_active,
      code= :code,
      longitude= :longitude,
      latitude= :latitude,
      radius= :radius,
      name= :name,
      details= :details,
      exp_date = :exp_date 
      WHERE id = :id;
        ;
    ";
      try {
      $statement = $this->db->prepare($query);
      $statement->execute(array(
        'id' => $id,
        'type' => $input['type'],
        'amount'  => $input['amount'],
        'is_active' => $input['is_active'],
        'longitude'  => $input['longitude'],
        'latitude'  => $input['latitude'],
        'radius'  => $input['radius'],
        'details'  => $input['details'],
        'exp_date'  => $input['exp_date'],
        'name'  => $input['name'],
        'code'=>$input['code']
      ));
      $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
    $response = new Response(["Promo Code Information Updated"],200);
    $response->get();
  }

  private function deletePromo($id)
  {
    $result = $this->find($id,true);

    if (! $result) {
      return $this->notFoundResponse();
    }

    $query = "
      DELETE FROM promo_codes
      WHERE id = :id;
    ";

    try {
      $statement = $this->db->prepare($query);
      $statement->execute(array('id' => $id));
      $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
    $response = new Response("Promo Code Deleted",200);
    $response->get();
  }

  public function find($id,$check=false)
  {
    $query = "
      SELECT
       *
      FROM
        promo_codes
      WHERE id = :id;
    ";

    try {
      $statement = $this->db->prepare($query);
      $statement->execute(array('id' => $id));
      $result = $statement->fetch(\PDO::FETCH_ASSOC);
      if($result){
      if($check)
      return true;
      $response = new Response($result,200);
      return $response->get();
    }
    return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }
  public function findByCode($code)
  {
    $query = "
      SELECT
       *
      FROM
        promo_codes
      WHERE code = :id;
    ";

    try {
      $statement = $this->db->prepare($query);
      $statement->execute(array('id' => $code));
      $result = $statement->fetch(\PDO::FETCH_ASSOC);
      if($result){
      return $result;
    }
    if(!$result) {
      $response = new Response("Promo Code Not Found",404);
      return $response->get();
    }
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  private function validatePromo($input)
  {
    $response = new Response('',400);
    if (! isset($input['amount']) || (int)$input['amount']<0 ) {
      $response->setContent('Error in Amount');
      $response->get();
    }
    if (! isset($input['type']) || ($input['type'] != PromoCodeTypes::fixed && $input['type'] != PromoCodeTypes::perc ) ) {
      $response->setContent('Error in type');
      $response->get();
      
    }
    if (!isset($input['longitude']) || !isset($input['latitude']) || !isset($input['radius']) || (int)$input['radius']<0  ) {
      $response->setContent('Error in location');
      $response->get();
      

    }
    if (! isset($input['exp_date']) ) {
      $response->setContent('Error in Expiration Date');
      $response->get();
      
    }

    return true;
  }

  private function unprocessableEntityResponse()
  {
    $response = new Response([
      'error' => 'Invalid input'
    ],422);
     $response->get();
    
  }

  private function notFoundResponse()
  {
    $response = new Response('',404);
     $response->get();
  }

  private function calculateDistance($lon1,$lat1,$lon2,$lat2)
  {
    if (($lat1 == $lat2) && ($lon1 == $lon2)) {
      return 0;
    }
    else {
      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      return (round($miles * 1.609344, 4));                     // Important Note here we return distance in KM unit instead of Mile
      }
    
  }

  private function promoApplicability()
  {
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
    $source= $input['ride']['source'];
    $destination= $input['ride']['destination'];
    $promo =  $this->findByCode($input['code']);
    $radius = $promo['radius'];
    $date = strtotime($promo['exp_date']);
    $now = strtotime(date('Y-m-d H:i:s'));
    if($date<$now){
      $response = new Response("This Promo Code is expired",405);
      $response->get();
    }
    if($promo['is_active']!=1){
      $response = new Response("This Promo Code is not active",405);
      $response->get();
    }
    $dist1 = $this->calculateDistance($promo['longitude'],$promo['latitude'],$source[0],$source[1]);
    $dist2 = $this->calculateDistance($promo['longitude'],$promo['latitude'],$destination[0],$destination[1]);
    if($dist1<$radius || $dist2<$radius){
      $response = new Response($promo,200);
      $response->get();
    }
    $response = new Response("You can not use this Promo Code",405);
      $response->get();


  }
}