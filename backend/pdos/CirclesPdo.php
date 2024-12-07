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








}
















?>