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
            if($method=="POST" && isset($data) && isset($id)){
                $this->create_recommendation($data,$userId,$id);
            }
            else if($method=='GET'&& empty($data) && isset($id)){
                 $this->get_recommendations($method,$userId,$id,$data);
            }
            else{
                $this->add_like($method,$userId,$id,$data);
            }
        }
       

    

    private function validate_date($date):bool{
        $format='Y-m-d';
        $formateddate=DateTime::createFromFormat($format,$date);
        return $formateddate&&$formateddate->format($format)===$date;

    }

    public function create_recommendation($data, $userId, $circleId) {
        
        if (empty($circleId)) {
            HttpResponse::send(400, null, ["error" => "Circle ID is required."]);
            return;
        }
    
        
        if (!$this->circleController->is_exist($circleId)) {
            HttpResponse::send(404, null, ["error" => "Circle not found. Please check the Circle ID."]);
            return;
        }
    
        
        if (!$this->memberController->is_member($userId, $circleId)) {
            HttpResponse::send(403, null, ["error" => "You are not a member of this circle."]);
            return;
        }
    
       
        $requiredFields = ['title', 'brief', 'link', 'date'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                HttpResponse::send(400, null, ["error" => "$field is required."]);
                return;
            }
        }
    
       
        if (!self::validate_date($data['date'])) {
            HttpResponse::send(400, null, ["error" => "Date must be in (dd, mm, yyyy) format."]);
            return;
        }
    
       
        $success = $this->recsPdo->create_recommendations(
            $data['title'],
            $data['brief'],
            $data['link'],
            $data['date'],
            $userId,
            $circleId
        );
    
        if ($success) {
            HttpResponse::send(201, null, ["message" => "Recommendation shared successfully."]);
        } else {
            HttpResponse::send(500, null, ["error" => "Internal server error."]);
        }
    }
    

    function get_recommendations($method,$userId,$id,$data){

    }
    function add_like(){

    }

    




}

















?>