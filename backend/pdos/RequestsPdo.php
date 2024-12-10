<?php


include_once 'db.php';
class RequestsPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }

    public function createRequest(int $userId, int $circleId, string $status, string $createdAt): bool
{
    $query = "INSERT INTO requests (userId, circleId, status, createdAt) VALUES (:userId, :circleId, :status, :createdAt)";
    $stmt = $this->pdo->prepare($query);

    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':circleId', $circleId, PDO::PARAM_INT);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':createdAt', $createdAt, PDO::PARAM_STR);

    return $stmt->execute();
}

public function getRequestByUserAndCircle(int $userId, int $circleId): ?array
{
    $query = "SELECT * FROM requests WHERE userId = :userId AND circleId = :circleId";
    $stmt = $this->pdo->prepare($query);

    
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':circleId', $circleId, PDO::PARAM_INT);

    $stmt->execute();

    
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    
    return $request ?: null;
}

public function getJoinRequestsByUserId(int $userId): array
{
    $query = "
        SELECT 
            r.id, 
            r.circleId, 
            c.name AS circleName, 
            c.description AS circleDesc, 
            r.status, 
            r.createdAt
        FROM 
            requests r
        INNER JOIN 
            circles c 
        ON 
            r.circleId = c.id
        WHERE 
            r.userId = :userId
        ORDER BY 
            r.createdAt DESC
    ";

    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}












































}














?>