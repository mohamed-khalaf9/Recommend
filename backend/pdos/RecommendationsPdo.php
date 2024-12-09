<?php
 include_once 'httpResponse.php';
 include_once 'db.php';
class RecommendationsPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }
    
    public function create_recommendations(string $title ,string $description,string $link,string $date, $userId,$circleId):bool{
      $userId=(int)$userId;
      $circleId=(int)$circleId;
      $sql="INSERT INTO recommendations (title,description,link,userId,circleId,createdAt)VALUES
      (:title, :description, :link,  :userId, :circleId, :createdAt)";
      $stm=$this->pdo->prepare($sql);
      return $stm->execute([
        ':title' => $title,
        ':description' => $description,
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
    public function get_recommendations($circleId):array{
      try{
      $sql="SELECT r.id,r.title,r.description,r.link,r.numberOfLikes,u.name FROM recommendations r INNER JOIN users u
      ON r.userId =u.id AND r.circleId= :circleId
      ORDER BY r.numberOfLikes DESC,r.createdAt DESC";
      $stm=$this->pdo->prepare($sql);
      $stm->execute([':circleId'=>$circleId]);
      $recommendations=$stm->fetchAll(PDO::FETCH_ASSOC);
      return $recommendations;
      }
      catch(PDOException $e){
        HttpResponse::send(500, null, ["error" => "Internal server error"]);
        exit;
      }


    }

}















?>