<?php

class MembersController{
    private $membersPdo;

    function __construct()
    {
        $db = new Database();
        $pdo = $db->getConnection();
        $this->membersPdo = new MembersPdo($pdo);
    }

    function processRequest($method,$userId,$id,$data){

    }




}


















?>