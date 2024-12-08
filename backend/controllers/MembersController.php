<?php
 include_once 'pdos/MembersPdo.php';
 include_once 'controllers/CirclesController.php';
 include_once 'httpResponse.php';
class MembersController{
    private $membersPdo;
    private $circleController;
    function __construct()
    {
        $db = new Database();
        $pdo = $db->getConnection();
        $this->membersPdo = new MembersPdo($pdo);
        $this->circleController=new CirclesController();
    }

    public function is_member($userId):bool{
          return $this->membersPdo->is_member($userId);
    }
    
    
    function processRequest($method,$userId,$id,$data){
     if($id==null){
        HttpResponse::send( 400,null,["errror" =>"circle id is required"]);
        return;
     }
     if(!($this->circleController->is_exist($id))){
        HttpResponse::send(404,null,["error"=>"Circle is not found"]);
     }
     else{
        if($method=="GET"){
            $this->get_circle_members();
        }
     }
    }
    public function get_circle_members(){
        $members=$this->membersPdo->get_cirle_members();
        if(empty($members)){
            HttpResponse::send(404,null,["error"=>"No members for that circle until now"]);
        }
        else{
            HttpResponse::send(200,null,["members "=>$members]);
        }
    }
   




}


















?>