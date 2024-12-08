<?php
include_once 'db.php';
include_once 'pdos/RecommendationsPdo.php';
include_once 'httpResponse.php';
include_once 'jwtHelper.php';
include_once 'MembersController.php';
include_once 'CirclesController.php';
class RecommendationsController{
    private $recsPdo;
    private $circleController;
    private $memberController;
    function __construct()
    {
        $db = new Database();
        $pdo = $db->getConnection();
        $this->recsPdo = new RecommendationsPdo($pdo);
        $this->circleController=new CirclesController();
        $this->memberController=new MembersController();
    }

   public  function processRequest($method,$userId,$id,$data){

       if($id==null){
        HttpResponse::send(400,null,["error"=> "circle ID is required"]);
       }
       else if( !($this->circleController->is_exist($id))){
        HttpResponse::send(404,null,["error"=>"NOT FOUND ,check your circle id"]);
       }
       else{
        if(!($this->memberController->is_member($userId))){
            HttpResponse::send(403,null,["error"=>"You are not a member of this circle"]);
        }
        else{
            if($method=='POST'&&count($data)>0){
                $this->create_recommendation($data,$userId,$id);
            }
            elseif($method=='GET'){
                 $this->get_recommendations();
            }
            else{
                $this->add_like($method,$userId,$id,$data);
            }
        }
        }
       }

    

    private function validate_date($date):bool{
        $format='Y-m-d';
        $formateddate=DateTime::createFromFormat($format,$date);
        return $formateddate&&$formateddate->format($format)===$date;

    }

    public function create_recommendation($data,$userId,$circleId){
        $fields=['title','brief','link','date'];
        foreach($fields as $field){
            if(empty($data[$field])){
                HttpResponse::send( 400,null,["errror" =>"$field is required"]);
                return;
            }
        }
       if(!(self::validate_date($data['date']))){
            $this->recsPdo->create_recommendations($data['title'],$data['brief'],$data['link'],$data['date'],$userId,$circleId);
            HttpResponse::send(400,null,["error" => "Date must be in (dd,mm,yyy) format"]);
            return;
             
       }
       else{
        if($this->recsPdo->create_recommendations($data['title'],$data['brief'],$data['link'],$data['date'],$userId,$circleId)){
            HttpResponse::send(201,null,["message" => "recommendation shared successfully"]);
        }
        else{
            HttpResponse::send(500,null,["error" => "Internal server error"]);
        }
       }
          
    }
    function get_recommendations(){
        $recommendations=$this->recsPdo->get_recommendations();
        if(empty($recommendations)){
            HttpResponse::send(200,null,["error"=>"No recommendations found at the moment"]);
        }
        else{
            HttpResponse::send(200,null,["recommendations "=>$recommendations]);
      }
}
    function add_like(){

    }

    




}

















?>