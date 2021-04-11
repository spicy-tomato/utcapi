<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/print_error.php";

    class Account
    {
        private const account_table = "Account";
        private const student_table = "Student";
        private const teacher_table = "Teacher";

        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect     = $connect;
        }

        public function getPermission ($username) : string
        {
            $sql_query =
                "SELECT
                    Permission
                FROM
                    " . self::account_table . "
                WHERE
                    Username = :username";

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':username' => $username]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return isset($data['Permission']) ? $data['Permission'] : 'Not Found';

            } catch (PDOException $error) {
                printError($error);

                return "Failed";
            }
        }


        public function getIDAccount ($id)
        {
            $sql_query = "
                    SELECT
                        a.id
                    FROM
                         " . self::student_table . " s,
                         " . self::teacher_table . " t,
                         " . self::account_table . " a  
                    WHERE
                        ((s.ID_Student = :ID AND s.ID = a.id) OR
                        (t.ID_Teacher = :ID AND t.ID = a.id)) 
                    ";

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute(['ID' => $id]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return $data['id'];

            } catch (PDOException $error) {
                printError($error);

                return null;
            }
        }
    }