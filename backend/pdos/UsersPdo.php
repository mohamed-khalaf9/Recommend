<?php

include_once 'db.php';
class UsersPdo{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }

    public function isEmailTaken(string $email): bool
    {
        $sql = "SELECT COUNT(*) AS count FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->execute([':email' => $email]); 
        $result = $stmt->fetch(PDO::FETCH_ASSOC); 
    
        return $result['count'] > 0; 
    }
    
    public function createUser(string $name, string $email, string $password, string $education, string $brief, string $date): bool
{
    $sql = "INSERT INTO users (name, email, password, education, brief, createdAt) VALUES (:name, :email, :password, :education, :brief, :date)";
    $stmt = $this->pdo->prepare($sql);

    return $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':password' => $password, 
        ':education' => $education,
        ':brief' => $brief,
        ':date' => $date,
    ]);
    }

    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    public function getUserProfile(int $userId): ?array {
        $query = "
            SELECT id, name, email, education, brief, createdAt 
            FROM users 
            WHERE id = :userId
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }


 


}















?>