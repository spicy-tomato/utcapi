<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . '/utcapi/api-v2/class/sinh_vien_controller.php';

    class LoginInfo
    {
        private const bang_csdl = "Department_Account";
        private PDO $conn;
        private string $username;
        private string $password;
        private string $department_name;

        public function __construct(PDO $conn, string $username, string $password)
        {
            $this->conn  = $conn;
            $this->username = $username;
            $this->password = $password;
        }

        public function login(): bool
        {
            $sqlQuery = "
                SELECT Notification_Department_Name
                FROM 
                    " . self::bang_csdl . "
                WHERE
                    Username = :username AND
                    Password = :password
                LIMIT 0, 1";

            try {
                $stmt = $this->conn->prepare($sqlQuery);
                $stmt->execute([
                    ':username' => $this->username,
                    ':password' => $this->password
                ]);

                $loggedAccount = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($loggedAccount) == 1) {
                    $this->department_name = $loggedAccount[0]['Notification_Department_Name'];
                    return true;
                }

                return false;

            } catch (PDOException $loi) {
                exit($loi->getMessage());
            }
        }

        public function getDepartmentName(): string
        {
            return $this->department_name;
        }
    }