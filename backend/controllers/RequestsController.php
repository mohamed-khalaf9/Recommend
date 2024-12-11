<?php

include_once 'db.php';
include_once 'pdos/RequestsPdo.php';
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
       
    }

    function processRequest($method, $userId, $id, $data)
    {
        if ($method == "GET" && empty($data) && isset($id)) {
            $this->get_pending_requests($userId, $id);
        }
        elseif ($method == "PUT" && isset($userId) && isset($id) && isset($data)) {
            if ($data['status'] == 'approved') {
                $this->approve_request($id, $userId);
            }
            else if ($data['status'] == 'rejected') {
                $this->reject_request($id,$userId);
            }
            else {
                HttpResponse::send(500,null,["message" => "invalid provided data "]);
            }
        }
        elseif ($method == "POST" && isset($id) && empty($data)) {
            $this->joinCircle($userId, $id);
        }
        elseif ($method == "GET" && empty($id) && empty($data)) {
            $this->getJoinRequests($userId);
        }
        else {
            HttpResponse::send(500,null,["message" => "invalid request "]);
        }
    }

    public function get_pending_requests($userId, $circleId)
    {

        $this->memberController = new MembersController();
        $this->circleController = new CirclesController();

        if (!$this->circleController->is_exist($circleId)) {
            HttpResponse::send(404, null, ["message" => "Not found"]);
            return;
        }
        if ($this->memberController->getUserRole($userId, $circleId) != 'Admin') {
            HttpResponse::send(403, null, ["message" => "You are not allowed, you are not the admin"]);
            return;
        }
        $requests = $this->reqsPdo->get_pending_requests($circleId);
        if ($requests) {
            HttpResponse::send(200, null, $requests);
        } else {
            HttpResponse::send(404, null, ["message" => "There are no pending requests"]);
        }
    }

    public function is_found($requestId): bool
    {
        return $this->reqsPdo->is_found($requestId);
    }

    public function get_status($requestId): string
    {
        return $this->reqsPdo->get_status($requestId);
    }

    public function approve_request($requestId, $userId)
{
    if (!$this->is_found($requestId)) {
        HttpResponse::send(404, null, ["message" => "Not found, check request id"]);
        return;
    }

 
    $circleId = $this->reqsPdo->getCircleIdFromRequestId($requestId);

    $this->memberController = new MembersController();
    if ($this->memberController->getUserRole($userId, $circleId) != 'Admin') {
        HttpResponse::send(403, null, ["message" => "You are not allowed, you are not the admin"]);
        return;
    }

 
    if ($this->get_status($requestId) == 'Approved') {
        HttpResponse::send(409, null, ["message" => "This request is already approved"]);
        return;
    }

    if ($this->get_status($requestId) == 'Rejected') {
        HttpResponse::send(409, null, ["message" => "This request is already rejected"]);
        return;
    }

    $userIdFromRequest = $this->reqsPdo->getUserIdFromRequestId($requestId);

    if ($userIdFromRequest === null) {
        // If no user is found, return a 404 not found error with a descriptive message
        HttpResponse::send(404, null, ["message" => "User not found for the provided request ID"]);
        return;
    }
  
    $approveSuccess = $this->reqsPdo->approve_request($requestId, $userIdFromRequest, $circleId);
    if ($approveSuccess) {
        HttpResponse::send(201, null, ["message" => "Request accepted and member added successfully to the circle"]);
    } else {
        HttpResponse::send(500, null, ["error" => "Internal server error"]);
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

             $this->circleController= new CirclesController();
            $circleExists = $this->circleController->isCircleExists($circleId);
            if (!$circleExists) {
                HttpResponse::send(404, null, ['error' => 'Circle not found.']);
                return;
            }

            $this->memberController=new MembersController();
            if ($this->memberController->isUserMember($userId, $circleId)) {
                HttpResponse::send(409, null, ['error' => 'User is already a member of this circle.']);
                return;
            }

            $existingRequest = $this->reqsPdo->getRequestByUserAndCircle($userId, $circleId);
            if ($existingRequest) {
                HttpResponse::send(409, null, ['error' => 'You have already requested to join this circle.']);
                return;
            }
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

    public function getJoinRequests($userId)
    {
        try {
            $joinRequests = $this->reqsPdo->getJoinRequestsByUserId($userId);

            if (empty($joinRequests)) {
                HttpResponse::send(404, null, ['message' => 'No join requests found for this user.']);
                return;
            }

            HttpResponse::send(200, null, $joinRequests);
        } catch (Exception $e) {
            HttpResponse::send(500, null, ['error' => 'An unexpected error occurred.', 'details' => $e->getMessage()]);
        }
    }

    public function reject_request($requestId,$userId)
    {
        if (!$this->is_found($requestId)) {
            HttpResponse::send(404, null, ["message" => "Not found, check request id"]);
            return;
        }
        $circleId = $this->reqsPdo->getCircleIdFromRequestId($requestId);
        $this->memberController = new MembersController();
        if ($this->memberController->getUserRole($userId, $circleId) != 'Admin') {
            HttpResponse::send(403, null, ["message" => "You are not allowed, you are not the admin"]);
            return;
        }
        if ($this->get_status($requestId) == 'Approved') {
            HttpResponse::send(409, null, ["message" => "This request is already approved"]);
            return;
        }
        if ($this->get_status($requestId) == 'Rejected') {
            HttpResponse::send(409, null, ["message" => "This request is already rejected"]);
            return;
        }
        $success = $this->reqsPdo->reject_request($requestId);
        if ($success) {
            HttpResponse::send(201, null, ["message" => "Request rejected"]);
        } else {
            HttpResponse::send(500, null, ["error" => "Internal server error"]);
        }
    }
}
?>



   
   




    














