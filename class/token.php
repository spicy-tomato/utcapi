<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/print_error.php";

    class Token
    {
        private PDO $connect;
        private string $student_id;
        private string $token;

        private const device_table = "Device";

        public function __construct (PDO $connect, string $student_id, string $token)
        {
            $this->connect    = $connect;
            $this->student_id = $student_id;
            $this->token      = $token;
        }

        public function upsert () : bool
        {
            $current_time = date('Y-m-d H:i:s');

            $sql_query =
                "INSERT INTO
                    " . self::device_table . "
                    (Device_Token, ID_Student, Last_Use)
                VALUES
                    ('" . $this->token . "', '" . $this->student_id . "', '" . $current_time . "')
                ON DUPLICATE KEY UPDATE
                    ID_Student = '" . $this->student_id . "',
                    Last_Use = '" . $current_time . "'";

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute();

                return true;

            } catch (Exception $error) {
                printError($error);

                return false;
            }
        }
    }
