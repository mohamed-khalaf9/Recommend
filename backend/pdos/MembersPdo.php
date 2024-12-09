<?php
include_once 'db.php';
class MembersPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }
    public function is_member($userId,$circleId):bool{
        $sql="SELECT userId FROM members WHERE userId=:userId AND circleId=:circleId";
        $stm=$this->pdo->prepare($sql);
        $stm->execute([':userId' => $userId,':circleId'=>$circleId]);
        return $stm->fetchColumn()!==false;
      }
      public function get_member_role($userId):string{
          $sql="SELECT m.role FROM members m WHERE m.userId=:userId ";
          $stm=$this->pdo->prepare($sql);
          $stm->execute([':userId'=>$userId]);
          $role=$stm->fetchColumn();
          if($role==null)
          return '';
        else
          return $role;
      }

      public function get_cirle_members():array{
        try{
        $sql="SELECT m.id,m.role,u.name,u.education,u.brief,m.createdAt FROM members m  INNER JOIN users u
        ON u.id=m.userId
        AND m.role<>'Admin'";

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

}
















?>