<?php

include_once 'pdos/CirclesPdo.php';
class CirclesController{
    private $circlesPdo;

    function __construct()
    {
        $db = new Database();
        $pdo = $db->getConnection();
        $this->circlesPdo = new CirclesPdo($pdo);
    }
      public function is_exist($circleId):bool{
          return $this->circlesPdo->is_exist($circleId);
      }
    function processRequest($method,$userId,$id,$data){

    }




}
















?>