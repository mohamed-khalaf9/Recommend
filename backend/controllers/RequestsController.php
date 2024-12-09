<?php
include_once 'db.php';
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




}

















?>