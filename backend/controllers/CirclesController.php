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
      public function is_exist($circleId):bool{
          return $this->circlesPdo->is_exist($circleId);
      }
    function processRequest($method,$userId,$id,$data){
          if($method=="DELETE"&&empty($data)&&isset($userId)&&isset($id)){
            $this->delete_circle($id);
          }
    }

    public function delete_circle($circleId){
       if(!$this->circlesPdo->is_exist($circleId)){
        HttpResponse::send(404,null,["error"=>"Not found,Check circle id"]);
        return;
       }
       $success=$this->circlesPdo->delete_circle($circleId);
       if($success){
        HttpResponse::send(202,null,["message"=>"circle deleted successfully"]);
       }
       else{
        HttpResponse::send(500,null,["error"=>"Internal server error"]);
       }
    }



}
















?>