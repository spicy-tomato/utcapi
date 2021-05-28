<?php
    include_once dirname(__DIR__) . '/shared/functions.php';

    class Token
    {
        private PDO $connect;
        private string $id_student;
        private string $token;

        private const device_table = 'Device';

        public function __construct (PDO $connect, string $student_id = '', string $token = '')
        {
            $this->connect    = $connect;
            $this->id_student = $student_id;
            $this->token      = $token;
        }

        public function upsert () : string
        {
            $current_time = date('Y-m-d H:i:s');

            $sql_query =
                'INSERT INTO
                    ' . self::device_table . '
                    (Device_Token, ID_Student, Last_Use)
                VALUES
                    (:token, :id_student, :current_time)
                ON DUPLICATE KEY UPDATE
                    ID_Student = :id_student,
                    Last_Use = :current_time';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':token' => $this->token,
                    ':id_student' => $this->id_student,
                    ':current_time' => $current_time
                ]);

                return 'OK';

            } catch (Exception $error) {
                printError($error);

                return 'Failed';
            }
        }
    }
