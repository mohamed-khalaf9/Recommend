<?php
 include_once 'httpResponse.php';

class RecommendationsPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }
    
    public function create_recommendations(string $title ,string $brief,string $link,string $date,int $userId,int $circleId):bool{
      $sql="INSERT INTO recommendations (title,brief,link,userId,circleId,createdAt)VALUES
      (:title, :brief, :link,  :userId, :circleId, :createdAt)";
      $stm=$this->pdo->prepare($sql);
      return $stm->execute([
        ':title' => $title,
        ':brief' => $brief,
        ':link'=> $link,
        ':userId' => $userId,
        ':circleId' => $circleId,
        ':createdAt' => $date
      ]
      );
      

    }
    public function is_found($rcmmndId):bool{
      $sql="SELECT id FROM recommendations WHERE id=:rcmmndId";
        $stm=$this->pdo->prepare($sql);
        $stm->execute([':rcmmndId' => $rcmmndId]);
        return $stm->fetchColumn()!==false;
    }
    public function get_recommendations():array{
      try{
      $sql="SELECT r.id,r.title,r.brief,r.link,r.numberOfLikes,u.name FROM recommendations r INNER JOIN users u
      ON r.userId = u.id
      ORDER BY r.createdAt ";
      $stm=$this->pdo->prepare($sql);
      $stm->execute();
      $recommendations=$stm->fetchAll(PDO::FETCH_ASSOC);
      return $recommendations;
      }
      catch(PDOException $e){
        HttpResponse::send(500, null, ["error" => "Internal server error"]);
        return [];
      }


    }

}















?>