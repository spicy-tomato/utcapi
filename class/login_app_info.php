<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";

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
                    a.Account_Username = ? AND 
                    a.Password = ? AND 
                    s.ID_Student = a.Account_Username";

            $stmt = $this->conn->prepare($sql_query);
            $stmt->execute(array($account['ID_Student'], md5($account['Password'])));

            $response = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$response) {
                $data['message'] = 'failed';
            }
            else {
                $data['message'] = 'success';
                $data['info'] = $response;
            }

            return $data;
        }
    }

    $db= new Database();
    $login = new LoginApp($db->connect());
    var_dump($login->checkAccount());


