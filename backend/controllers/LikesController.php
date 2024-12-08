<?php

include_once 'pdos/LikesPdo.php';
include_once 'RecommendationsController.php';
//include_once 'MembersController.php';
//include_once 'CirclesController.php';
class LikesController{
    private $likesPdo;
    //private $circleController;
    //private $memberController;
    private $recController;
    function __construct()
    {
        $db = new Database();
        $pdo = $db->getConnection();
        $this->likesPdo = new LikesPdo($pdo);
        //$this->circleController=new CirclesController();
        //$this->memberController=new MembersController();
        $this->recController=new RecommendationsController();
    }
    
    function processRequest($method,$userId,$id,$data){
        
          
        if($method=="POST"&&isset($id)&&empty($data)){
             $this->add_like($userId,$id);
        }
        else{
            HttpResponse::send(404,null,["error"=>"Not found"]);
        }
}
    
    

    public function add_like( $userId, $recID){
        if(empty($recID)){
            HttpResponse::send(400, null, ["error" => "Recommendation ID is required."]);
            return;
        }
        if(!$this->recController->is_found($recID)){
            HttpResponse::send(404,null,["error"=>"Not found"]);
            return;
        }
        $success=$this->likesPdo->add_like($userId,$recID);
          if($success){
            HttpResponse::send(201,null,["message"=>"you liked this recommendation"]);
          }
          else{
            HttpResponse::send(500,null,["error"=>"Internal server error"]);
          }

    }




}















?>