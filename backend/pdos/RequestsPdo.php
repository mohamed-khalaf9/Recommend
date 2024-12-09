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
          ON u.id=r.userId AND r.status='Pending'";
          $stm=$this->pdo->prepare($sql);
          $stm->execute();
          $requests=$stm->fetchAll(PDO::FETCH_ASSOC);
          return $requests;
        }catch(PDOException $e){
            HttpResponse::send(500, null, ["error" => "Internal server error"]);
            exit;
        }
        
    }

}














?>