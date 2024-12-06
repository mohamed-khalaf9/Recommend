<?php


class UsersController{
    private $usersPdo;

    function __construct()
    {
        $db = new Database();
        $pdo = $db->getConnection();
        $this->usersPdo = new UsersPdo($pdo);
    }


    function processRequest($method,$data){

    }




}
















?>