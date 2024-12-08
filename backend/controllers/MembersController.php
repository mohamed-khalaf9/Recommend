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
        $this->circleController=new CirclesController();    }

    public function is_member($userId,$circleId):bool{
          return $this->membersPdo->is_member($userId,$circleId);
    }
    
    
    function processRequest($method,$userId,$id,$data){

            if($method=="GET"&&empty($data)&&isset($id)){
                $this->get_circle_members($id);
            }
         }

        public function get_circle_members($circleId){
            if (empty($circleId)) {
                HttpResponse::send(400, null, ["error" => "Circle ID is required."]);
                return;
            }
    
    
            if (!$this->circleController->is_exist($circleId)) {
                HttpResponse::send(404, null, ["error" => "Circle not found. Please check the Circle ID."]);
                return;
            }
    
            $members=$this->membersPdo->get_cirle_members();
            if(empty($members)){
                HttpResponse::send(404,null,["error"=>"No members for that circle until now"]);
            }
            else{
                HttpResponse::send(200,null,["members "=>$members]);
            }
        }        }    
   























?>