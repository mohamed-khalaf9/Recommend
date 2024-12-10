<?php
include_once 'db.php';
class MembersPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }

   
  public function is_member($userId, $circleId): bool {
    $sql = "SELECT userId 
            FROM members 
            WHERE userId = :userId AND circleId = :circleId";
    $stm = $this->pdo->prepare($sql);
    $stm->execute([
        ':userId' => $userId,
        ':circleId' => $circleId
    ]);
    return $stm->fetchColumn() !== false;
}


    function createMember($userId,$circleId,$role,$createdAt)
    {
        $sql = "INSERT INTO members (userId, circleId, role, createdAt) VALUES (:userId, :circleId, :role, :createdAt)";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':userId' => $userId,
                ':circleId' => $circleId,
                ':role' => $role,
                ':createdAt' => $createdAt
            ]);
    
            return true;
        } catch (PDOException $e) {
            return false;
        }

    }

    public function getMemberByUserAndCircle(int $userId, int $circleId): ?array
{
    $query = "SELECT * FROM members WHERE userId = :userId AND circleId = :circleId";
    $stmt = $this->pdo->prepare($query);


    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':circleId', $circleId, PDO::PARAM_INT);

    $stmt->execute();

    
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    return $member ?: null;
}

































































      public function get_cirle_members():array{
        try{
        $sql="SELECT m.id,m.role,u.name,u.education,u.brief,m.createdAt FROM members m  INNER JOIN users u
        ON u.id=m.userId
        AND m.role<>'Admin'";

        $stm=$this->pdo->prepare($sql);
        $stm->execute();
        $members=$stm->fetchAll(PDO::FETCH_ASSOC);
        return $members;
        }
        
        catch(PDOException $e){
            HttpResponse::send(500, null, ["error" => "Internal server error"]);
        return [];
        }
      }

}
















?>
