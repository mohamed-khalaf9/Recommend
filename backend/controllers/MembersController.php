<?php

include_once 'pdos/MembersPdo.php';
class MembersController{
    private $membersPdo;

    function __construct()
    {
        $db = new Database();
        $pdo = $db->getConnection();
        $this->membersPdo = new MembersPdo($pdo);
    }

    function processRequest($method,$userId,$id,$data){

    }


    public function createMember(array $data): bool
{
    
    if (empty($data['userId']) || empty($data['circleId']) || empty($data['role']) || empty($data['createdAt'])) {
        return false;
    }

    $userId = $data['userId'];
    $circleId = $data['circleId'];
    $role = $data['role'];
    $createdAt = $data['createdAt'];

    try {
        $result = $this->membersPdo->createMember($userId, $circleId, $role, $createdAt);

        return $result; 
    } catch (Exception $e) {
        return false; 
    }
}





}


















?>