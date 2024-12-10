<?php


include_once 'db.php';
class RequestsPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }
    public function get_pending_requests($circleId):array{
        try{
          $sql="SELECT r.id,r.userId,u.name,u.education,u.brief,u.createdAt FROM requests r INNER JOIN users u
          ON u.id=r.userId WHERE r.status='Pending'AND r.circleId=:circleId";
          $stm=$this->pdo->prepare($sql);
          $stm->execute([':circleId'=>$circleId]);
          $requests=$stm->fetchAll(PDO::FETCH_ASSOC);
          $formatedRequests=array_map(function($request){
                 return[
                    "requestId"=>$request['id'],
                    "userId"=>$request['userId'],
                    "username"=>$request['name'],
                    "education"=>$request['education'],
                     "brief"=>$request['brief'],
                     "createdAt"=>$request['createdAt']

                 ];
          },$requests);
          return  $formatedRequests;
        }catch(PDOException $e){
            HttpResponse::send(500, null, ["error" => "Internal server error"]);
            exit;
        }
    }
    public function is_found($requestId):bool{
        $sql="SELECT id FROM requests WHERE id=:requestId";
        $stm=$this->pdo->prepare($sql);
        $stm->execute([':requestId' => $requestId]);
        return $stm->fetchColumn()!==false;
    }
    public function get_status($requestId):string{
        $sql="SELECT status FROM requests WHERE id=:requestId";
        $stm=$this->pdo->prepare($sql);
         $stm->execute([':requestId' => $requestId]);
         return $stm->fetchColumn();
    }
        public function approve_request($requestId):bool{
            $requestId=(int)$requestId;
                 $sql="UPDATE requests 
                 SET status =:val
                 WHERE id=:requestId";
                 $stm=$this->pdo->prepare($sql);
                 return $stm->execute([
                    ':val'=>'Approved',
                    ':requestId'=>$requestId
                 ]);

    }
    public function reject_request($requestId):bool{
        $requestId=(int)$requestId;
             $sql="UPDATE requests 
             SET status =:val
             WHERE id=:requestId";
             $stm=$this->pdo->prepare($sql);
             return $stm->execute([
                ':val'=>'Rejected',
                ':requestId'=>$requestId
             ]);

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
    
        
    }

















?>