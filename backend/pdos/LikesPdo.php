<?php
include_once 'db.php';
class LikesPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }
    public function is_liked($recId,$userId): bool {
        $sql = "SELECT COUNT(*) FROM likes WHERE userId = :userId AND recId = :recId";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(['userId' => $userId, 'recId' => $recId]);
        $numOfLikes = $stm->fetchColumn();
        return $numOfLikes > 0;
    }
    
    public function add_like($userId, $recID): bool
    {
        $recID = (int)$recID;
        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO likes (userId, recId, createdAt) VALUES (:userId, :recId, CURDATE())";
            $stm = $this->pdo->prepare($sql);
            if (!$stm->execute([':userId' => $userId, ':recId' => $recID])) {
                throw new Exception("Failed to insert like");
            }
    
            // Update the number of likes in recommendations table
            $sql2 = "UPDATE recommendations SET numberOfLikes = COALESCE(numberOfLikes, 0) + 1 WHERE id = :recId";
            $stm2 = $this->pdo->prepare($sql2);
            if (!$stm2->execute([':recId' => $recID])) {
                throw new Exception("Failed to update recommendation likes count");
            }
    
            // Commit the transaction
            $this->pdo->commit();
    
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error in add_like: " . $e->getMessage());
            HttpResponse::send(500, null, ["error" => $e->getMessage()]);
    
            return false;
        }
    }
    

    
}
















?>