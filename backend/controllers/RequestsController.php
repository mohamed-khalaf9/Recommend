<?php

include_once 'db.php';
include_once'pdos/RequestsPdo.php';
include_once 'controllers/CirclesController.php';
include_once 'controllers/MembersController.php';
include_once 'pdos/CirclesPdo.php';


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
          else if($method=="PUT"&&isset($userId)&&isset($id)&&isset($data)){
              if($data['status']=='approved'){
                  $this->approve_request($id,$userId);

              }
              if($data['status']=='rejected'){
                $this->reject_request($id);

              }
          }
         else if($method=="POST"&& isset($id)&& empty($data))
         {
            $this->joinCircle($userId,$id);
         }
        else if($method=="GET" && empty($id)&& empty($data))
        {
            $this->getJoinRequests($userId);
        }
    }

    public function get_pending_requests($userId,$circleId){
        if(!$this->circleController->is_exist($circleId)){
          HttpResponse::send(404,null,["message"=>"Not found"]);
          return;
        }
       if($this->memberController->get_member_role($userId)!='Admin'){
        HttpResponse::send(403,null,["message"=>"You are not allowed,you are not the admin "]);
        return;
       }
       $requests=$this->reqsPdo->get_pending_requests($circleId);
       if($requests){
        HttpResponse::send(200,null,$requests);
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

    public function approve_request($requestId,$userId){
         if(! $this->is_found($requestId)){
            HttpResponse::send(404,null,["message"=>"Not found ,check request id"]);
          return;
         }
         if($this->memberController->get_member_role($userId)!='Admin'){
            HttpResponse::send(404,null,["message"=>"You are not allowed,you are not the admin"]);
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



    

    public function joinCircle($userId, $circleId): void
{
    // Validate input
    if (!is_numeric($userId) || !is_numeric($circleId) || $userId <= 0 || $circleId <= 0) {
        HttpResponse::send(400, null, ['error' => 'Invalid input: userId and circleId must be positive numbers.']);
        return;
    }

    try {
        $userId = (int)$userId;
        $circleId = (int)$circleId;

        // Check if the circle exists
        $circlesController = new CirclesController();
        $circleExists = $circlesController->isCircleExists($circleId);
        if (!$circleExists) {
            HttpResponse::send(404, null, ['error' => 'Circle not found.']);
            return;
        }

        // Check if the user is already a member
        $membersController = new MembersController();
        if ($membersController->isUserMember($userId, $circleId)) {
            HttpResponse::send(409, null, ['error' => 'User is already a member of this circle.']);
            return;
        }

        // Check if a similar request already exists
        $existingRequest = $this->reqsPdo->getRequestByUserAndCircle($userId, $circleId);
        if ($existingRequest) {
            HttpResponse::send(409, null, ['error' => 'You have already requested to join this circle.']);
            return;
        }

        // Create a new join request
        $createdAt = date('Y-m-d');
        $status = 'Pending';

        $result = $this->reqsPdo->createRequest($userId, $circleId, $status, $createdAt);

        if ($result) {
            HttpResponse::send(201, null, ['message' => 'Join circle request created successfully.']);
        } else {
            HttpResponse::send(500, null, ['error' => 'Failed to create join circle request.']);
        }
    } catch (Exception $e) {
        HttpResponse::send(500, null, ['error' => 'An unexpected error occurred.', 'details' => $e->getMessage()]);
    }
}

function getJoinRequests($userId)
{
    try {

        
        $joinRequests = $this->reqsPdo->getJoinRequestsByUserId($userId);


        if (empty($joinRequests)) {
            HttpResponse::send(404, null, ['message' => 'No join requests found for this user.']);
            return;
        }

        HttpResponse::send(200, null,$joinRequests);
    } catch (Exception $e) {
        HttpResponse::send(500, null, ['error' => 'An unexpected error occurred.', 'details' => $e->getMessage()]);
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





   
   




}

















?>