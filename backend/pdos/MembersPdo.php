<?php

class MembersPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }
    function is_member($userId):bool{
        $sql="SELECT userId FROM members WHERE userId=:userId";
        $stm=$this->pdo->prepare($sql);
        $stm->execute([':userId' => $userId]);
        return $stm->fetchColumn()!==false;
      }

}
















?>