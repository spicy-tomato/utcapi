<?php
    include_once dirname(__DIR__) . '/shared/functions.php';

    class Device
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

        public function getTokenByIdStudent ($id_student_list) : array
        {
            $part_of_sql = '';

            foreach ($id_student_list as $id_student) {
                $part_of_sql .= '\'' . $id_student . '\',';
            }

            $part_of_sql = rtrim($part_of_sql, ',');

            $sql_query = '
                SELECT 
                    Device_Token
                FROM
                    ' . self::device_table . '
                WHERE 
                    ID_Student IN (' . $part_of_sql . ')
                 ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute();
                $record = $stmt->fetchAll(PDO::FETCH_COLUMN);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function upsertToken () : array
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
                    Last_Use = :current_time
                 ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':token' => $this->token,
                    ':id_student' => $this->id_student,
                    ':current_time' => $current_time
                ]);

                $data['status_code'] = 200;
                $data['content']     = 'OK';

                return $data;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function deleteOldToken ($old_token)
        {
            $sql_query = '
                DELETE 
                FROM 
                     ' . self::device_table . '
                WHERE Device_Token = :old_token
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':old_token' => $old_token]);

            } catch (PDOException $error) {
                throw $error;
            }
        }
    }