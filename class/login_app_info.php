<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/print_error.php";

    class LoginApp
    {
        private const account_table_name = "Account";
        private const student_table_name = "Student";
        private PDO $conn;

        public function __construct (PDO $conn)
        {
            $this->conn = $conn;
        }

        public function checkAccount () : array
        {
            $account = json_decode(file_get_contents("php://input"), true);

            $sql_query = "
                SELECT
                    s.* 
                FROM 
                     " . self::account_table_name . " a, 
                     " . self::student_table_name . " s 
                WHERE 
                    a.Username = ? AND 
                    a.password = ? AND 
                    s.ID_Student = a.Username";


            try {
                $stmt = $this->conn->prepare($sql_query);
                $stmt->execute(array($account['ID'], md5($account['Password'])));

                $response = $stmt->fetch(PDO::FETCH_ASSOC);
                unset($response['id']);

            } catch (PDOException $e) {
                printError($e);

                $data['message'] = 'failed';
                return $data;
            }

            if (!$response) {
                $data['message'] = 'failed';
            }
            else {
                unset($response['ID']);

                $data['message'] = 'success';
                $data['info'] = $response;
            }

            return $data;
        }
    }

