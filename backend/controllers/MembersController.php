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

    public function is_member($userId,$circleId):bool{
          return $this->membersPdo->is_member($userId,$circleId);
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


public function isUserMember(int $userId, int $circleId): bool
{
    try {
        // check membership
       
       $member = $this->membersPdo->getMemberByUserAndCircle($userId, $circleId);
        return $member !== null;
    } catch (Exception $e) {
        return false;
    }
}






}


















?>