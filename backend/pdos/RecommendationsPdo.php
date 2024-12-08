<?php


class RecommendationsPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }
    
    public function create_recommendations(string $title ,string $brief,string $link,string $date,int $userId,int $circleId):bool{
      $sql="INSERT INTO recommendations (title,description,link,userId,circleId,createdAt)VALUES
      (:title, :description, :link,  :userId, :circleId, :createdAt)";
      $stm=$this->pdo->prepare($sql);
      return $stm->execute([
        ':title' => $title,
        ':description' => $brief,
        ':link'=> $link,
        ':userId' => $userId,
        ':circleId' => $circleId,
        ':createdAt' => $date
      ]
      );
      

    }

}















?>