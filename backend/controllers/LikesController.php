<?php



class LikesController{
    private $likesPdo;

    function __construct()
    {
        $db = new Database();
        $pdo = $db->getConnection();
        $this->likesPdo = new LikesPdo($pdo);
    }
    
    function processRequest($method,$userId,$id,$data){

    }




}















?>