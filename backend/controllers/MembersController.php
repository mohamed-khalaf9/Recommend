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
   




}


















?>