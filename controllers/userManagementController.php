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
        if (empty($data)) {
            echo '<tr><td colspan="7" style="text-align: center;">No user found.</td></tr>';
            return;
        }
        foreach ($data as $index => $row) {
            $qs = http_build_query(['userId' => $row['id']]);
            $username = htmlspecialchars($row['username']);
            $fullName = htmlspecialchars($row['full_name']);
            $role = htmlspecialchars($row['role']);
            $statusName = htmlspecialchars($row['name']);
            $email = htmlspecialchars($row['email']);

            echo "<tr>
                <td style='width: 5%;'>" . ($index + 1) . "</td>
                <td>{$username}</td>
                <td>{$fullName}</td>
                <td>{$role}</td>
                <td><label class='status-badge {$statusName}'>{$statusName}</label></td>
                <td>{$email}</td>
                <td>";
            echo "<a class='btn btn-sm btn-outline-primary action-btn' href='index.php?route=UserManagement/userUpdate&{$qs}' class='btn btn-sm btn-primary'><i class='fa fa-edit'></i></a>";
            echo "</td></tr>";
        }
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
