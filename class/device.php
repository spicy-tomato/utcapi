<?php

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
            if (empty($id_student_list[0])) {
                return [];
            }

            $sql_query_1 =
                'CREATE TEMPORARY TABLE temp3 (
                  ID_Student varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

            $sql_of_list =
                implode(',', array_fill(0, count($id_student_list), '(?)'));

            $sql_query_2 =
                'INSERT INTO temp3
                    (ID_Student)
                VALUES
                    ' . $sql_of_list;

            $sql_query_3 =
                'SELECT
                    Device_Token
                FROM
                     temp3 t
                         RIGHT OUTER JOIN
                     ' . self::device_table . ' d
                        ON t.ID_Student = d.ID_Student';

            try {
                $stmt = $this->connect->prepare($sql_query_1);
                $stmt->execute();

                $stmt = $this->connect->prepare($sql_query_2);
                $stmt->execute($id_student_list);

                $stmt = $this->connect->prepare($sql_query_3);
                $stmt->execute();
                $record = $stmt->fetchAll(PDO::FETCH_COLUMN);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function upsertToken () : array
        {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
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

                $data['status_code'] = 200;
                $data['content']     = 'OK';

                return $data;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function deleteOldTokens ($invalid_token_list)
        {
            if (empty($invalid_token_list)) {
                return;
            }

            $sql_of_list =
                implode(',', array_fill(0, count($invalid_token_list), '?'));

            $sql_query =
                'DELETE
                FROM
                     ' . self::device_table . '
                WHERE 
                    Device_Token IN (' . $sql_of_list . ')';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute($invalid_token_list);

            } catch (PDOException $error) {
                throw $error;
            }
        }
    }