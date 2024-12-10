<?php

include_once 'pdos/LikesPdo.php';
include_once 'RecommendationsController.php';

class LikesController {
    private $likesPdo;
    private $recController;

    public function __construct($likesPdo, $recController) {
        $this->likesPdo = $likesPdo;
        $this->recController = $recController;
    }

    public function processRequest($method, $userId, $id, $data) {
        if ($method === "POST" && isset($id) && empty($data)) {
            $this->addLike($userId, $id);
        } else {
            HttpResponse::send(404, null, ["error" => "Not found"]);
        }
    }

    private function addLike($userId, $recID) {
        if (empty($recID)) {
            HttpResponse::send(400, null, ["error" => "Recommendation ID is required."]);
            return;
        }

        if (!$this->recController->is_found($recID)) {
            HttpResponse::send(404, null, ["error" => "Recommendation not found."]);
            return;
        }

        if ($this->likesPdo->is_liked($recID, $userId)) {
            HttpResponse::send(400, null, ["error" => "This recommendation is already liked."]);
            return;
        }

        $success = $this->likesPdo->add_like($userId, $recID);

        if ($success) {
            HttpResponse::send(201, null, ["message" => "You liked this recommendation."]);
        } else {
            HttpResponse::send(500, null, ["error" => "Internal server error."]);
        }
    }
}
