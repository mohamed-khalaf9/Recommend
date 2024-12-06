<?php

class RequestsController{
    private $reqsPdo;

    function __construct()
    {
        $db = new Database();
        $pdo = $db->getConnection();
        $this->reqsPdo = new RequestsPdo($pdo);
    }

    function processRequest($method,$userId,$id,$data){

    }




}

















?>