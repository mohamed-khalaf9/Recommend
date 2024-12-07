<?php

include_once 'pdos/CirclesPdo.php';
include_once 'controllers/MembersController.php';
class CirclesController{
    private $circlesPdo;

    function __construct()
    {
        $db = new Database();
        $pdo = $db->getConnection();
        $this->circlesPdo = new CirclesPdo($pdo);
    }

    function processRequest($method,$userId,$id,$data){
        if($method=="POST" && empty($id))
        {
            if(count($data)==3)
            {
                $this->createCircle($data,$userId);
            }

        }
        else if($method == "GET" && empty($id) && empty($data))
        {
            $this->getUserCircles($userId);

        }
       

    }

    function createCircle($data,$userId)
    {

    if (empty($data['name']) || empty($data['desc']) || empty($data['date'])) {
        HttpResponse::send(400, null, ['error' => 'All fields (name, desc, date) are required.']);
        return;
    }

    $name = $data['name'];
    $desc = $data['desc'];
    $date = $data['date'];

    // Validate the date format (YYYY-MM-DD)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        HttpResponse::send(400, null, ['error' => 'Invalid date format. Use YYYY-MM-DD.']);
        return;
    }

    try {
        $circleId = $this->circlesPdo->createCircle($name, $desc, $date);

        if (!$circleId) {
            HttpResponse::send(500, null, ['error' => 'Failed to create the circle.']);
            return;
        }

        
        $membersController = new MembersController();
        $createdAt = date('Y-m-d'); 
        $membersController->createMember([
            'userId' => $userId,
            'circleId' => $circleId,
            'role' => 'Admin',
            'createdAt' => $createdAt
        ]);

        HttpResponse::send(201, null, ['message' => 'Circle created successfully.']);
    } catch (Exception $e) {
        HttpResponse::send(500, null, ['error' => 'An error occurred: ' . $e->getMessage()]);
    }

    }

    public function isCircleExists(int $circleId): bool
{
    
    if ($circleId <= 0) {
        return false; 
    }

    try {  
        $circle = $this->circlesPdo->getCircleById($circleId);
        return $circle !== null;
    } catch (Exception $e) {
        return false; 
    }
}

function getUserCircles($userId)
{
    try {
        $circles = $this->circlesPdo->getUserCircles($userId);

        if (empty($circles)) {
            HttpResponse::send(404, null, ['error' => 'No circles found for this user.']);
            return;
        }

        
        $response = array_map(function ($circle) {
            return [
                'id' => $circle['id'],
                'name' => $circle['name'],
                'desc' => $circle['desc'],
                'role' => $circle['role']
            ];
        }, $circles);

        HttpResponse::send(200, null, $response);
    } catch (Exception $e) {
        HttpResponse::send(500, null, ['error' => 'Failed to fetch circles.', 'details' => $e->getMessage()]);
    }

}


   

    














}
















?>