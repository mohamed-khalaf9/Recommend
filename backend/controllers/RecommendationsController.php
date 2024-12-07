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
        $circleController=new CirclesController();
        $memberController=new MembersController();
    }

    function processRequest($method,$userId,$id,$data){

       if($id==null){
        HttpResponse::send(400,null,["error"=> "circle ID is required"]);
       }
       if( !($this->circleController->is_exist($id))){
        HttpResponse::send(404,null,["error"=>"NOT FOUND ,check your circle id"]);
       }
       else{
        if(!($this->memberController->is_member($userId))){
            HttpResponse::send(403,null,["error"=>"You are not a member of this circle"]);
        }
        else{
            if(count($data)>0&&$method=='POST'){
                $this->create_recommendation($data,$userId,$id);
            }
            elseif($method=='GET'){
                 $this->get_recommendations($method,$userId,$id,$data);
            }
            else{
                $this->add_like($method,$userId,$id,$data);
            }
        }
        }
       }

    

    function validate_date($date):bool{
        $format='Y-m-d';
        $formateddate=DateTime::createFromFormat($format,$date);
        return $formateddate&&$formateddate->format($format)===$date;

    }

    function create_recommendation($data,$userId,$circleId){
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
    function get_recommendations($method,$userId,$id,$data){

    }
    function add_like(){

    }

    




}

















?>