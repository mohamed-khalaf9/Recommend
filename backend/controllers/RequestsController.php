<?php

include_once 'pdos/CirclesPdo.php';
include_once 'pdos/RequestsPdo.php';
class RequestsController{
    private $reqsPdo;

    function __construct()
    {
        $db = new Database();
        $pdo = $db->getConnection();
        $this->reqsPdo = new RequestsPdo($pdo);
    }

    function processRequest($method,$userId,$id,$data){
        if($method=="POST"&& isset($id)&& empty($data))
        {
            $this->joinCircle($userId,$id);
        }


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





   
   




}

















?>