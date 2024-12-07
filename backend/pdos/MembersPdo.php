<?php

class MembersPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
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

}
















?>