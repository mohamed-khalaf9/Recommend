<?php

class RecommendationsController{
    private $recsPdo;

    function __construct()
    {
        $db = new Database();
        $pdo = $db->getConnection();
        $this->recsPdo = new RecommendationsPdo($pdo);
    }

    function processRequest($method,$userId,$id,$data){

    }




}

















?>