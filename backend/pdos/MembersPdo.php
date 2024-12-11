<?php
include_once 'db.php';
class MembersPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }
  
    public function get_user_role_in_circle(int $userId, int $circleId): string {
        try {
            $sql = "SELECT m.role 
                    FROM members m 
                    WHERE m.userId= :userId AND m.circleId = :circleId";
    
            $stm = $this->pdo->prepare($sql);
            $stm->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stm->bindParam(':circleId', $circleId, PDO::PARAM_INT);
            $stm->execute();
    
            $role = $stm->fetchColumn();
    
            return $role ?? '';
        } catch (PDOException $e) {
            HttpResponse::send(500, null, ["error" => "Internal server error"]);
            exit;
        }
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


public function createMember(array $data): bool {
    $sql = "INSERT INTO members (userId, circleId, role, createdAt) 
            VALUES (:userId, :circleId, :role, :createdAt)";
    
    try {
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':userId', $data['userId'], PDO::PARAM_INT);
        $stmt->bindParam(':circleId', $data['circleId'], PDO::PARAM_INT);
        $stmt->bindParam(':role', $data['role'], PDO::PARAM_STR);
        $stmt->bindParam(':createdAt', $data['createdAt'], PDO::PARAM_STR);

        if (!$stmt->execute()) {
            error_log("Failed to execute query: " . json_encode($stmt->errorInfo()));
            return false;
        }

        return true;
    } catch (PDOException $e) {
        error_log("PDOException in createMember: " . $e->getMessage());
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
    



public function get_circle_members(int $circleId): array {
    try {
        $sql = "SELECT m.id, m.role, u.name, u.education, u.brief, m.createdAt 
                FROM members m 
                INNER JOIN users u ON u.id = m.userId
                WHERE m.circleId = :circleId AND m.role <> 'Admin'";

        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':circleId', $circleId, PDO::PARAM_INT);
        $stm->execute();
        $members = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $members;
    } catch (PDOException $e) {
        HttpResponse::send(500, null, ["error" => "Internal server error"]);
        exit;
    }
}

      public function is_found($memberId):bool{
        $sql="SELECT id FROM members WHERE id=:memberId";
        $stm=$this->pdo->prepare($sql);
        $stm->execute([':memberId' => $memberId]);
        return $stm->fetchColumn()!==false;
      }

      public function remove_member($memberId):bool{
        $sql="DELETE FROM members 
        WHERE id =:memberId";
        $stm=$this->pdo->prepare($sql);
        return $stm->execute([':memberId'=>$memberId]);
      }

      public function leave_circle($userId, $circleId): bool {
        try {
            $sql = "DELETE FROM members WHERE userId = :userId AND circleId = :circleId";
            $stmt = $this->pdo->prepare($sql);
    
            $stmt->execute([':userId' => $userId, ':circleId' => $circleId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
    

}
















?>
