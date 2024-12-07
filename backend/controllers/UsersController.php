<?php

include_once 'db.php';
include_once 'pdos/UsersPdo.php';
include_once 'httpResponse.php';
include_once 'jwtHelper.php';
class UsersController{
    private $usersPdo;

    function __construct()
    {
        $db = new Database();
        $pdo = $db->getConnection();
        $this->usersPdo = new UsersPdo($pdo);
    }


    function processRequest($method,$userId,$data){
        if($method== "POST")
        {
            if(count($data)== 6)
            $this->signup($data);
            else if(count($data)==2)
            $this->login($data);
            else{
                http_response_code(404);
                echo json_encode(["error" => "Data is not valid"]);

            }
        }
        else if($method=="GET"&&empty($id)&&empty($data))
        {
            $this->getUsersInfo($userId);


        }

    }

    function signup($data): void{

        // Validate required fields
        $requiredFields = ['name', 'email', 'password', 'education', 'brief', 'date'];
        foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            HttpResponse::send(400, null, ["error" => "$field is required"]);
            return;
        }
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        HttpResponse::send(400, null, ["error" => "Invalid email format"]);
        return;
     }

     $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

     if ($this->usersPdo->isEmailTaken($data['email'])) {
        HttpResponse::send(409, null, ["error" => "Email already registered"]);
        return;
    }
    $created = $this->usersPdo->createUser(
        $data['name'],
        $data['email'],
        $data['password'],
        $data['education'],
        $data['brief'],
        $data['date']
    );

    if ($created) {
        HttpResponse::send(201, null, ["message" => "Signup successful"]);
    } else {
        HttpResponse::send(500, null, ["error" => "Internal server error"]);
    }

    }

    function login($data): void{
        if (empty($data['email']) || empty($data['password'])) {
            HttpResponse::send(400, null, ['error' => 'Email and password are required.']);
            return;
        }

        $email = $data['email'];
        $password = $data['password'];

        
        $user = $this->usersPdo->getUserByEmail($email);
        $userId = $user['id'];

        if ($user && password_verify($password, $user['password'])) {
            $token = JwtHelper::createToken($userId); 
            
            HttpResponse::send(200, null, ['message' => 'Login successful', 'token' => $token]);
        } else {
            HttpResponse::send(401, null, ['error' => 'Invalid email or password.']);
        }

    }

    function getUsersInfo($userId)
    {

    }























    }




















?>