<?php
include_once 'db.php';
class CirclesPdo
{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public  function is_exist($circleId): bool
    {
        $sql = "SELECT id FROM circles WHERE id=:circleId";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([':circleId' => $circleId]);
        return $stm->fetchColumn() !== false;
    }
    public function delete_circle($circleId): bool
    {
        $circleId = (int)$circleId;
        $sql = "DELETE FROM circles
          WHERE id=:circleId";
        $stm = $this->pdo->prepare($sql);
        return $stm->execute([':circleId' => $circleId]);
    }

    public function createCircle(string $name, string $descr, string $createdAt, int $userId): bool
    {
        try {
            // Begin transaction
            $this->pdo->beginTransaction();

            // Insert into circles table
            $circleSql = "INSERT INTO circles (name, description, createdAt) VALUES (:name, :descr, :createdAt)";
            $circleStmt = $this->pdo->prepare($circleSql);
            $circleStmt->bindParam(':name', $name, PDO::PARAM_STR);
            $circleStmt->bindParam(':descr', $descr, PDO::PARAM_STR);
            $circleStmt->bindParam(':createdAt', $createdAt, PDO::PARAM_STR);

            if (!$circleStmt->execute()) {
                $this->pdo->rollBack();
                return false;
            }

            // Get the ID of the newly inserted circle
            $circleId = $this->pdo->lastInsertId();

            // Insert into members table with role 'Admin'
            $memberSql = "INSERT INTO members (userId, circleId, role, createdAt) VALUES (:userId, :circleId, :role, :createdAt)";
            $memberStmt = $this->pdo->prepare($memberSql);
            $role = 'Admin';
            $memberStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $memberStmt->bindParam(':circleId', $circleId, PDO::PARAM_INT);
            $memberStmt->bindParam(':role', $role, PDO::PARAM_STR);
            $memberStmt->bindParam(':createdAt', $createdAt, PDO::PARAM_STR);

            if (!$memberStmt->execute()) {
                $this->pdo->rollBack();
                return false;
            }

            // Commit transaction
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            // Roll back transaction in case of error
            $this->pdo->rollBack();
            return false;
        }
    }






    public function getUserCircles(int $userId): array
    {
        $query = "
        SELECT c.id, c.name, c.`description` AS `desc`, m.role 
        FROM members m
        JOIN circles c ON m.circleId = c.id
        WHERE m.userId = :userId
    ";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getCircleById(int $circleId): ?array
    {
        $sql = "SELECT id, name, description, createdAt FROM circles WHERE id = :circleId";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':circleId', $circleId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $circle = $stmt->fetch(PDO::FETCH_ASSOC);
                return $circle ?: null; // Return the circle details or null if not found
            } else {
                return null; // Query execution failed
            }
        } catch (PDOException $e) {
            error_log("PDO Exception in getCircleById: " . $e->getMessage());
            return null; // Return null in case of an exception
        }
    }
}
