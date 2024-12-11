<?php

include_once 'pdos/CirclesPdo.php';
include_once 'controllers/MembersController.php';


class CirclesController
{
    private $circlesPdo;
    private $memberController;


    function __construct()
    {
        $db = new Database();
        $pdo = $db->getConnection();
        $this->circlesPdo = new CirclesPdo($pdo);
    }

    function processRequest($method, $userId, $id, $data)
    {
        if ($method === "POST" && empty($id) && isset($data)) {
            if (count($data) === 3) {
                $this->createCircle($data, $userId);
            } else {
                HttpResponse::send(500, null, ["message" => "fields are not valid"]);
            }
        } elseif ($method === "DELETE" && empty($data) && isset($id)) {
            $this->delete_circle($id, $userId);
        } elseif ($method === "GET" && empty($id) && empty($data)) {
            $this->getUserCircles($userId);
        } 
        else if($method=="GET" && empty($data) && isset($id)){
            $this->getCircleInfo($userId,$id);

        }
        else {
            HttpResponse::send(400, null, ["message" => "Invalid request"]);
        }
    }

    public function is_exist($circleId): bool
    {
        return $this->circlesPdo->is_exist($circleId);
    }

    public function delete_circle($circleId, $userId)
    {
        if (!$this->circlesPdo->is_exist($circleId)) {
            HttpResponse::send(404, null, ["error" => "Not found,Check circle id"]);
            return;
        }
         
        $this->memberController = new MembersController();
        if ($this->memberController->getUserRole($userId, $circleId) != 'Admin') {
            HttpResponse::send(404, null, ["error" => "You are not allowed,you are not the admin"]);
            return;
        }
        $success = $this->circlesPdo->delete_circle($circleId);
        if ($success) {
            HttpResponse::send(202, null, ["message" => "circle deleted successfully"]);
        } else {
            HttpResponse::send(500, null, ["error" => "Internal server error"]);
        }
    }





    function createCircle($data, $userId)
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
            $circleId = $this->circlesPdo->createCircle($name, $desc, $date,$userId);

            if (!$circleId) {
                HttpResponse::send(500, null, ['error' => 'Failed to create the circle.']);
                return;
            }


          

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

    public function getCircleInfo($userId, $circleId)
    {
        $circleInfo = $this->circlesPdo->getCircleById($circleId);
        if ($circleInfo != null) {

            HttpResponse::send(200, null, $circleInfo);
        } else {
            HttpResponse::send(404, ["message" => "Circle not found"]);
        }
    }
    
}


?>