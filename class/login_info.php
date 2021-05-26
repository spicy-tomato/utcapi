<?php
    include_once dirname(__DIR__) . '/shared/functions.php';

    class LoginInfo
    {
        private const account_table = 'Account';
        private const other_department_table = 'Other_Department';
        private const department_table = 'Department';
        private const faculty_table = 'Faculty';


        private PDO $connect;
        private string $department_name;
        private int $account_id;
        private string $username;
        private string $password;

        public function __construct (PDO $connect, string $username, string $password)
        {
            $this->connect  = $connect;
            $this->username = $username;
            $this->password = $password;
        }

        public function login () : bool
        {
            $sql_query = '
                SELECT 
                    a.id, 
                    od.Other_Department_Name, 
                    od.ID AS OT_ID, 
                    d.Department_Name, 
                    d.ID AS D_ID, 
                    f.Faculty_Name, 
                    f.ID AS F_ID 
                FROM 
                    ' . self::account_table . ' a, 
                    ' . self::other_department_table . ' od, 
                    ' . self::department_table . ' d, 
                    ' . self::faculty_table . ' f  
                WHERE 
                    (a.username = :username AND 
                    a.password = :password) AND 
                    (od.ID = a.id OR d.ID = a.ID OR f.ID = a.id) 
                LIMIT 0, 1';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':username' => $this->username,
                    ':password' => md5($this->password)
                ]);

                $loggedAccount = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($loggedAccount) == 1) {
                    $this->account_id = $loggedAccount[0]['id'];
                    if ($this->account_id == $loggedAccount[0]['OT_ID']) {
                        $this->department_name = $loggedAccount[0]['Other_Department_Name'];
                    }
                    else {
                        if ($this->account_id == $loggedAccount[0]['F_ID']) {
                            $this->department_name = $loggedAccount[0]['Faculty_Name'];
                        }
                        else {
                            $this->department_name = $loggedAccount[0]['Department_Name'];
                        }
                    }
                    return true;
                }

                return false;

            } catch (PDOException $error) {
                printError($error);

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