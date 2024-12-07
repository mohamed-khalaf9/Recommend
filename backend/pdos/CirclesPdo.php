<?php

class CirclesPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }

    public function createCircle(string $name, string $desc, string $createdAt): ?int
{
    $sql = "INSERT INTO circles (name, description, createdAt) VALUES (:name, :desc, :createdAt)";

    try {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':desc' => $desc,
            ':createdAt' => $createdAt
        ]);
        return $this->pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error creating circle: " . $e->getMessage());
        return null;
    }
}


public function getCircleById(int $circleId): ?array
{
    $query = "SELECT * FROM circles WHERE id = :circleId";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':circleId', $circleId, PDO::PARAM_INT);
    $stmt->execute();

    $circle = $stmt->fetch(PDO::FETCH_ASSOC);

    return $circle ?: null; // Return circle details or null if not found
}


public function getUserCircles(int $userId): array {
    $query = "
        SELECT c.id, c.name, c.description AS desc, m.role 
        FROM members m
        JOIN circles c ON m.circleId = c.id
        WHERE m.userId = :userId
    ";

    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}









}
















?>