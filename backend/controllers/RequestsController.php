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
        if($method=="POST"&& isset($id)&& empty($data))
        {
            $this->joinCricle($userId,$id);
        }


    }

    function joinCricle($userId,$circleId) // create request
    {
        

    }

   




}

















?>