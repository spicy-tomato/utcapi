<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/print_error.php";

    class LoginInfo
    {
        private const account_table = "Account";
        private const other_department_table = "Other_Department";
        private const faculty_table = "Faculty";


        private PDO $conn;
        private string $department_name;
        private int $account_id;
        private string $username;
        private string $password;

        public function __construct (PDO $conn, string $username, string $password)
        {
            $this->conn = $conn;
            $this->username = $username;
            $this->password = $password;
        }

        public function login () : bool
        {
            $sqlQuery = "
                SELECT 
                    a.id, 
                    od.Other_Department_Name, 
                    od.ID AS O_ID, 
                    f.Faculty_Name, 
                    f.ID AS F_ID 
                FROM 
                    " . self::account_table . " a, 
                    " . self::other_department_table . " od, 
                    " . self::faculty_table . " f  
                WHERE 
                    (a.Username = :username AND 
                    a.password = :password) AND 
                    (od.ID = a.id OR f.ID = a.id) 
                LIMIT 0, 1";

            try {
                $stmt = $this->conn->prepare($sqlQuery);
                $stmt->execute([
                    ':username' => $this->username,
                    ':password' => md5($this->password)
                ]);

                $loggedAccount = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($loggedAccount) == 1) {
                    if ($loggedAccount[0]['id'] == $loggedAccount[0]['O_ID']) {
                        $this->department_name = $loggedAccount[0]['Other_Department_Name'];
                    }
                    else {
                        $this->department_name = $loggedAccount[0]['Faculty_Name'];
                    }
                    $this->account_id = $loggedAccount[0]['id'];
                    return true;
                }

                return false;

            } catch (PDOException $e) {
                printError($e);

                return false;
            }
        }

        public function getDepartmentName () : string
        {
            return $this->department_name;
        }

        public function getAccountID () : string
        {
            return $this->account_id;
        }
    }