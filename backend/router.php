<?php

use Firebase\JWT\JWK;

include_once 'db.php';
include_once 'controllers/LikesController.php';
include_once 'controllers/CirclesController.php';
include_once 'controllers/MembersController.php';
include_once 'controllers/RecommendationsController.php';
include_once 'controllers/UsersController.php';
include_once 'controllers/RequestsController.php';

include_once 'jwtHelper.php'; 

class Router {

    function __construct()
    {
        
    }

   
    function fetchToken($authorizationHeader) {
        if($authorizationHeader!==null){
        if (preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            return $matches[1]; 
        }
    }
        return null; 
    
    }

    
    function fetchUserIdFromToken($token) {
  
        if ($token) {
            $decoded = JwtHelper::decodeToken($token); 
            return $decoded->userId ?? null; 
        }
        return null;
    }

  
     // Route the request to the appropriate controller
    function route($method, $authorizationHeader, $resource, $id, $data) {
        $token = $this->fetchToken($authorizationHeader);
        $userId = $this->fetchUserIdFromToken($token);


        if ($resource == "users") {
            $usersController = new UsersController();
            $usersController->processRequest($method,$userId,$data);
        }
        else{
            if (JwtHelper::verifyToken($token)==true){

                if ($resource == "circles") {
                    $circlesController = new CirclesController();
                    $circlesController->processRequest($method,$userId,$id,$data);
                }
                else if($resource== "requests")
                {
                    $requestsController = new RequestsController();
                    $requestsController->processRequest($method,$userId,$id,$data);
                }
                else if($resource== "recommendations")
                {
                    $recsController = new RecommendationsController();
                    $recsController->processRequest($method,$userId,$id,$data);
                }
                else if($resource== "likes")
                {
                    $likesController = new LikesController();
                    $likesController->processRequest($method,$userId,$id,$data);
                }
                else if($resource== "members")
                {
                    $memsController = new MembersController();
                    $memsController->processRequest($method,$userId,$id,$data);
                }
                else
                {
                    http_response_code(404);
                    echo json_encode(["error" => "Resource not found"]);
                }
            

            }

            else{
                HttpResponse::send(401,null,["error" => "Unauthorized"]);
                return;
            }

        }
       

    }
    



    
    

}


?>