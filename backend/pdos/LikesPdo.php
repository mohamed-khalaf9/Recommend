<?php

class LikesPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }
    public function add_like($userId,$recID,$createdAt):bool{
        $sql="INSERT INTO likes VALUES
        (:userId,:recId,:createdAt)";
        $stm=$this->pdo->prepare($sql);
        $sql2="UPDATE recommendations
        SET numberOfLikes=numberOfLikes+1
        WHERE id=:recId";
        $stm2=$this->pdo->prepare($sql2);
        $stm2->execute([':recId'=>$recID]);
       return $stm->execute([
            ':userId' =>$userId,
            ':recId' =>$recID,
            ':createdAt' =>$createdAt
        ]
        )
        ;
    }


    
}
















?>