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
        if($method== "POST")
        {
            if(count($data)== 6)
            $this->signup($data);

            else{
                http_response_code(404);
                echo json_encode(["error" => "Data is not valid"]);

            }
        }

    }

    function signup($data): void{

    }




}
















?>