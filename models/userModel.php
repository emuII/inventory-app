<?php
class userModel
{
    protected $db;
    public function __construct($db)
    {
        $this->db = $db;
    }
    public function GetAllUsers()
    {
        $filter_number      = htmlentities($_POST['filter_number'] ?? '');
        $sql = "select * from m_user mu
                join m_status ms on mu.status = ms.value and ms.code ='general';";

        $params = [];

        if ($filter_number !== '') {
            $sql .= " AND mu.username LIKE ?";
            $params[] = "%$filter_number%";
        }
        $row = $this->db->prepare($sql);

        $row->execute($params);
        $response = $row->fetchAll();
        return $response;
    }

    public function addUser($payload = [])
    {
        $username   = $payload['username'] ?? '';
        $full_name  = $payload['full_name'] ?? '';
        $email      = $payload['email'] ?? '';
        $role       = $payload['role'] ?? '';
        $status     = $payload['user_status'] ?? '';

        try {
            if (
                $username === '' ||
                $full_name === '' ||
                $email === '' ||
                $role === '' ||
                $status === ''
            ) {
                return [
                    'ok' => false,
                    'message' => 'Data tidak lengkap'
                ];
            }


            $plain_password = $username . '123';
            $password = md5($plain_password);

            $sql = "INSERT INTO m_user (username, full_name, email, role, status, password) 
                VALUES (:username, :full_name, :email, :role, :status, :password)";
            $row = $this->db->prepare($sql);

            $response = $row->execute([
                ':username' => $username,
                ':full_name' => $full_name,
                ':email' => $email,
                ':role' => $role,
                ':status' => $status,
                ':password' => $password
            ]);

            if ($response) {
                return [
                    'ok' => true,
                    'username' => $username,
                    'message' => 'User berhasil dibuat. Password default: ' . $plain_password
                ];
            }

            return [
                'ok' => false,
                'message' => $response
            ];
        } catch (Throwable $e) {
            return [
                'ok' => false,
                'message' => 'Gagal insert user: ' . $e->getMessage()
            ];
        }
    }

    public function GetUserById($id)
    {
        $sql = "SELECT 
                    mu.id,
                    mu.username,
                    mu.full_name,
                    mu.email,
                    mu.role,
                    st.name statusName
                FROM m_user mu
                JOIN m_status st on mu.status = st.value and st.code = 'general' 
            WHERE mu.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ?: null;
    }

    public function UpdateUser($payload = [])
    {
        $id         = $payload['userId'] ?? '';
        $username   = $payload['username'] ?? '';
        $full_name  = $payload['full_name'] ?? '';
        $email      = $payload['email'] ?? '';
        $role       = $payload['role'] ?? '';
        $status     = $payload['user_status'] ?? '';

        try {
            if (
                $username === '' ||
                $full_name === '' ||
                $email === '' ||
                $role === '' ||
                $status === ''
            ) {
                return [
                    'ok' => false,
                    'message' => 'Data tidak lengkap'
                ];
            }

            $sql = "UPDATE m_user 
                    SET username = :username, 
                        full_name = :full_name, 
                        email = :email, 
                        role = :role, 
                        status = :status
                    WHERE id = :id";
            $row = $this->db->prepare($sql);

            $response = $row->execute([
                ':username' => $username,
                ':full_name' => $full_name,
                ':email' => $email,
                ':role' => $role,
                ':status' => $status,
                ':id' => $id
            ]);

            if ($response) {
                return [
                    'ok' => true,
                    'message' => 'User berhasil diupdate.'
                ];
            }

            return [
                'ok' => false,
                'message' => 'Gagal mengupdate user.'
            ];
        } catch (Throwable $e) {
            return [
                'ok' => false,
                'message' => 'Gagal update user: ' . $e->getMessage()
            ];
        }
    }

    public function ChangePassword($payload = [])
    {
        $userId          = $payload['userId'] ?? '';
        $newPassword     = $payload['new_password'] ?? '';
        $currentPassword = $payload['current_password'] ?? '';

        try {
            // 1. Validasi input
            if ($userId === '' || $newPassword === '' || $currentPassword === '') {
                return [
                    'ok' => false,
                    'message' => 'Data tidak lengkap'
                ];
            }

            if ($currentPassword === $newPassword) {
                return [
                    'ok' => false,
                    'message' => 'Password baru tidak boleh sama dengan password lama'
                ];
            }

            // 2. Ambil password lama dari DB
            $sql = "SELECT password FROM m_user WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return [
                    'ok' => false,
                    'message' => 'User tidak ditemukan'
                ];
            }

            // 3. Cek password lama
            if (md5($currentPassword) !== $user['password']) {
                return [
                    'ok' => false,
                    'message' => 'Password lama salah'
                ];
            }

            // 4. Update password baru
            $hashedPassword = md5($newPassword);
            $update = $this->db->prepare(
                "UPDATE m_user SET password = :password WHERE id = :id"
            );

            $result = $update->execute([
                ':password' => $hashedPassword,
                ':id'       => $userId
            ]);

            if ($result) {
                return [
                    'ok' => true,
                    'message' => 'Password berhasil diubah'
                ];
            }

            return [
                'ok' => false,
                'message' => 'Gagal mengubah password'
            ];
        } catch (Throwable $e) {
            return [
                'ok' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}
