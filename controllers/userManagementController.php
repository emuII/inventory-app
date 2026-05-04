<?php
class userManagementController
{
    protected $users;
    private $helper;
    public function __construct($db)
    {
        $this->users = new userModel($db);
        $this->helper = new helperModel($db);
    }

    public function GetAllUsers()
    {
        $data = $this->users->GetAllUsers();
        helperModel::json(200, 'Success', $data);
    }

    public function AddUser()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $payload = json_decode(file_get_contents('php://input'), true);
        $response = $this->users->addUser($payload);
        if ($response) {
            echo json_encode(['ok' => true, 'message' => $response['message']]);
        } else {
            echo json_encode(['ok' => false, 'message' => $response['message']]);
        }
    }

    public function userUpdate()
    {
        $userId = $_GET['userId'] ?? null;
        if (!$userId) {
            echo "user Id not found.";
            return;
        }
        $userDetail = $this->users->GetUserById($userId);
        $helper = $this->helper->getStatus("general");
        include 'views/UserManagements/userUpdate.php';
    }

    public function UpdateUser()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        $response = $this->users->UpdateUser($payload);
        if ($response['ok']) {
            echo json_encode(['ok' => true, 'message' => $response['message']]);
        } else {
            echo json_encode(['ok' => false, 'message' => $response['message']]);
        }
    }

    public function ChangePassword()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        $response = $this->users->ChangePassword($payload);
        if ($response['ok']) {
            echo json_encode(['ok' => true, 'message' => $response['message']]);
        } else {
            echo json_encode(['ok' => false, 'message' => $response['message']]);
        }
    }
}
