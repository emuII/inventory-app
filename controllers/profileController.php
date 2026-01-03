<?php
class profileController
{
    protected $users;
    public function __construct($db)
    {
        $this->users = new userModel($db);
    }

    public function ViewProfile()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $userId = $_SESSION['active_login']['id'] ?? null;

        if (!$userId) {
            echo "User not logged in.";
            return;
        }
        $userDetail = $this->users->GetUserById($userId);
        include 'views/Profile/index.php';
    }
}
