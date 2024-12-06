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

    function processRequest($method,$userId,$id,$data){

    }




}
















?>