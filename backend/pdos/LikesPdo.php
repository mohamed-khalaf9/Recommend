<?php
include_once 'db.php';
class LikesPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }
    public function is_liked($recID):bool{
        $sql="SELECT COUNT(*) FROM likes WHERE recId=:recId";
        $stm=$this->pdo->prepare($sql);
        $stm->execute(['recId'=>$recID]);
        $numOfLikes= $stm->fetchColumn();
        return $numOfLikes>0;
    }
    public function add_like( $userId, $recID):bool{
        $recID = (int)$recID;
        $sql="INSERT INTO likes (userId,recId,createdAt) VALUES
        (:userId,:recId,CURDATE())";
        $stm=$this->pdo->prepare($sql);
        if (!($stm->execute([':userId' => $userId, ':recId' => $recID]))) {
            HttpResponse::send(500, null, ["error" => "Failed to insert like"]);
            return false;
        }
        $sql2="UPDATE recommendations
        SET numberOfLikes=numberOfLikes+1
        WHERE id=:recId";
        $stm2=$this->pdo->prepare($sql2);
       if(!($stm2->execute([':recId'=>$recID]))){
        HttpResponse::send(500, null, ["error" => "Failed to insert like"]);
            return false;
       }

       return true;
    }


    
}
















?>