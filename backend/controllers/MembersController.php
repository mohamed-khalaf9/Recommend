<?php

include_once 'pdos/MembersPdo.php';
include_once 'controllers/CirclesController.php';
include_once 'httpResponse.php';

class MembersController {
    private $membersPdo;
    private $circleController;

    public function __construct() {
        $db = new Database();
        $pdo = $db->getConnection();
        $this->membersPdo = new MembersPdo($pdo);
        
    }

    
    public function processRequest($method, $userId, $id, $data) {
        try {
            if ($method === "GET" && isset($id)&&empty($data)) {
                $this->getCircleMembers($userId, $id);
            } elseif ($method === "DELETE" && !empty($data) && !empty($id)) {
                $this->removeMember($userId, $data, $id);
            }else if($method === "DELETE" && isset($id) && empty($data))
            {
               $this->leaveCircle($userId,$id);
            }
             else {
                HttpResponse::send(404, null, ["error" => "Invalid request"]);
            }
        } catch (Exception $e) {
            HttpResponse::send(500, null, ["error" => "An unexpected error occurred."]);
        }
    }

    
    public function isMember(int $userId, int $circleId): bool {
        try {
            return $this->membersPdo->is_member($userId, $circleId);
        } catch (Exception $e) {
            return false;
        }
    }

   
    public function getUserRole($userId,int $circleId): string {
        try {
            return $this->membersPdo->get_user_role_in_circle($userId,$circleId)?: '';
        } catch (Exception $e) {
            return '';
        }
    }

    
    public function getCircleMembers(int $userId, int $circleId) {
        try {
            if (empty($circleId)) {
                HttpResponse::send(400, null, ["error" => "Circle ID is required."]);
                return;
            }

            $this->circleController = new CirclesController();

            if (!$this->circleController->is_exist($circleId)) {
                HttpResponse::send(404, null, ["error" => "Circle not found."]);
                return;
            }

            $userRole = $this->membersPdo->get_user_role_in_circle($userId, $circleId);

            if ($userRole !== 'Admin') {
                HttpResponse::send(403, null, ["error" => "You are not authorized to view the members of this circle."]);
                return;
            }

            $members = $this->membersPdo->get_circle_members($circleId);

            if (!$members) {
                HttpResponse::send(404, null, ["error" => "No members found for this circle."]);
            } else {
                HttpResponse::send(200, null, $members);
            }
        } catch (Exception $e) {
            HttpResponse::send(500, null, ["error" => "An internal server error occurred."]);
        }
    }

   
    public function isFound(int $memberId): bool {
        try {
            return $this->membersPdo->is_found($memberId);
        } catch (Exception $e) {
            return false;
        }
    }

   
    public function removeMember(int $userId, array $data, $memberId) {
        try {
            if (empty($data['circleId'])) {
                HttpResponse::send(400, null, ["error" => "Circle ID is required."]);
                return;
            }

            $circleId = $data['circleId'];
            $userRole = $this->membersPdo->get_user_role_in_circle($userId, $circleId);

            if ($userRole !== 'Admin') {
                HttpResponse::send(403, null, ["error" => "You are not authorized to remove members from this circle."]);
                return;
            }

            if (empty($memberId)) {
                HttpResponse::send(400, null, ["error" => "Member ID is required."]);
                return;
            }

            if (!$this->isFound($memberId)) {
                HttpResponse::send(404, null, ["error" => "Member not found."]);
                return;
            }

            $success = $this->membersPdo->remove_member($memberId);

            if ($success) {
                HttpResponse::send(201, null, ["message" => "Member removed successfully."]);
            } else {
                HttpResponse::send(500, null, ["error" => "Failed to remove member due to a server issue."]);
            }
        } catch (Exception $e) {
            HttpResponse::send(500, null, ["error" => "An unexpected server error occurred."]);
        }
    }

    
    public function createMember(array $data): bool {
        try {
            if (empty($data['userId']) || empty($data['circleId']) || empty($data['role']) || empty($data['createdAt'])) {
                error_log("Validation failed for createMember: " . json_encode($data));
                return false;
            }
    
            $result = $this->membersPdo->createMember($data);
            if (!$result) {
                error_log("Failed to insert member into members table.");
            }
            return $result;
        } catch (Exception $e) {
            error_log("Exception in createMember: " . $e->getMessage());
            return false;
        }
    }
    
   
    public function isUserMember(int $userId, int $circleId): bool {
        try {
            $member = $this->membersPdo->getMemberByUserAndCircle($userId, $circleId);
            return $member !== null;
        } catch (Exception $e) {
            return false;
        }
    }



    public function leaveCircle($userId, $circleId)
{
    
    $member = $this->membersPdo->getMemberByUserAndCircle($userId, $circleId);
    if (!$member) {
        HttpResponse::send(404, null, ["message" => "Unauthorized action or member not found in this circle."]);
        return;
    }

    
    $success = $this->membersPdo->leave_circle($userId, $circleId);

    if ($success) {
        HttpResponse::send(200, null, ["message" => "Member successfully removed from the circle."]);
    } else {
        HttpResponse::send(500, null, ["error" => "Failed to remove the member."]);
    }
}












}

?>
