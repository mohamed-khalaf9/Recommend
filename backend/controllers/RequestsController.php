<?php
include_once 'db.php';
include_once'pdos/RequestsPdo.php';
include_once 'CirclesController.php';
include_once 'MembersController.php';
class RequestsController{
    private $reqsPdo;
   private $circleController;
   private $memberController;
    function __construct()
    {
        $db = new Database();
        $pdo = $db->getConnection();
        $this->reqsPdo = new RequestsPdo($pdo);
        $this->circleController=new CirclesController();
        $this->memberController=new MembersController();
    }

    function processRequest($method,$userId,$id,$data){
          if($method=="GET"&&isset($userId)&&isset($id)){
            $this->get_pending_requests($userId,$id);
          }
          if($method=="PUT"&&isset($userId)&&isset($id)&&isset($data)){
              if($data['status']=='approved'){
                  $this->approve_request($id);
              }
              if($data['status']=='rejected'){
                $this->reject_request($id);
              }
          }
    }

    public function get_pending_requests($userId,$circleId){
        if(!$this->circleController->is_exist($circleId)){
          HttpResponse::send(404,null,["message"=>"Not found"]);
          return;
        }
       if($this->memberController->get_member_role($userId)!='Admin'){
        HttpResponse::send(403,null,["error"=>"You are not allowed,you are not the admin "]);
        return;
       }
       $requests=$this->reqsPdo->get_pending_requests($circleId);
       if($requests){
        HttpResponse::send(200,null,["message"=>$requests]);
       }
       else{
        HttpResponse::send(404,null,["message"=>"There are no pending requests "]);
       }
    }
    public function is_found($requestId):bool{
    return $this->reqsPdo->is_found($requestId);
    }
    public function get_status($requestId):string{
         return $this->reqsPdo->get_status($requestId);
    }
    public function approve_request($requestId){
         if(! $this->is_found($requestId)){
            HttpResponse::send(404,null,["message"=>"Not found ,check request id"]);
          return;
         }
         if($this->get_status($requestId)=='Approved'){
            HttpResponse::send(409,null,["message"=>"This request is already approved"]);
            return;
         }
         if($this->get_status($requestId)=='Rejected'){
            HttpResponse::send(409,null,["message"=>"This request is already Rejected"]);
            return;
         }
         $success=$this->reqsPdo->approve_request($requestId);
         if($success){
            HttpResponse::send(201,null,["message"=> "request accepted and member added successfully to the circle"]);
         }
         else{
            HttpResponse::send(500,null,["error" => "Internal server error"]);
         }

    }
    public function reject_request($requestId){
        if(! $this->is_found($requestId)){
           HttpResponse::send(404,null,["message"=>"Not found ,check request id"]);
         return;
        }
        if($this->get_status($requestId)=='Approved'){
           HttpResponse::send(409,null,["message"=>"This request is already approved"]);
           return;
        }
        if($this->get_status($requestId)=='Rejected'){
           HttpResponse::send(409,null,["message"=>"This request is already Rejected"]);
           return;
        }
        $success=$this->reqsPdo->reject_request($requestId);
        if($success){
           HttpResponse::send(201,null,["message"=> "request accepted and member added successfully to the circle"]);
        }
        else{
           HttpResponse::send(500,null,["error" => "Internal server error"]);
        }

   }




}

















?>