<?php
include_once 'db.php';
class MembersPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }
    public function is_member($userId):bool{
        $sql="SELECT userId FROM members WHERE userId=:userId";
        $stm=$this->pdo->prepare($sql);
        $stm->execute([':userId' => $userId]);
        return $stm->fetchColumn()!==false;
      }

      public function get_cirle_members():array{
        try{
        $sql="SELECT m.id,m.role,u.name,u.education,u.brief,m.createdAt FROM members m  INNER JOIN users u
        ON u.id=m.userId";

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