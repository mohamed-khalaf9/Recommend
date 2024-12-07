<?php



class RequestsPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }

    public function createRequest(int $userId, int $circleId, string $status, string $createdAt): bool
{
    $query = "INSERT INTO requests (user_id, circle_id, status, created_at) VALUES (:userId, :circleId, :status, :createdAt)";
    $stmt = $this->pdo->prepare($query);

    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':circleId', $circleId, PDO::PARAM_INT);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':createdAt', $createdAt, PDO::PARAM_STR);

    return $stmt->execute();
}


}














?>