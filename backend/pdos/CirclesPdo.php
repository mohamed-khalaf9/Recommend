<?php

class CirclesPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }
    public  function is_exist($circleId):bool{
        $sql="SELECT id FROM circles WHERE id=:circleId";
        $stm=$this->pdo->prepare($sql);
        $stm->execute([':circleId'=>$circleId]);
        return $stm->fetchColumn()!==false;
    }
    







}
















?>