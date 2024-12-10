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

}














?>