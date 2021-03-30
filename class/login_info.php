<?php

    class LoginInfo
    {
        private const department_account_table = "Account";
        private const department_other_department_table = "Other_Department";

        private PDO $conn;
        private string $department_name;
        private int $account_id;
        private string $username;
        private string $password;

        public function __construct(PDO $conn, string $username, string $password)
        {
            $this->conn     = $conn;
            $this->username = $username;
            $this->password = $password;
        }

        public function login(): bool
        {
            $sqlQuery = "
                SELECT 
                    a.id, 
                    od.Other_Department_Name 
                FROM 
                    " . self::department_account_table . " a, 
                    " . self::department_other_department_table . " od 
                WHERE 
                    a.Username = :username AND 
                    a.password = :password AND 
                    od.ID = a.id 
                LIMIT 0, 1";

            try {
                $stmt = $this->conn->prepare($sqlQuery);
                $stmt->execute([
                    ':username' => $this->username,
                    ':password' => md5($this->password)
                ]);

                $loggedAccount = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($loggedAccount) == 1) {
                    $this->department_name = $loggedAccount[0]['Other_Department_Name'];
                    $this->account_id = $loggedAccount[0]['id'];
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

        public function getAccountID(): string
        {
            return $this->account_id;
        }
    }