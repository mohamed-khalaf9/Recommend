<?php

class MembersPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }
  public function is_member($userId, $circleId): bool {
    $sql = "SELECT userId 
            FROM members 
            WHERE userId = :userId AND circleId = :circleId";
    $stm = $this->pdo->prepare($sql);
    $stm->execute([
        ':userId' => $userId,
        ':circleId' => $circleId
    ]);
    return $stm->fetchColumn() !== false;
}


}
















?>
