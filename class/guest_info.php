<?php

    class GuestInfo
    {
        private const guest_info_table = 'Guest_Info';

        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function insert ($data)
        {
            $sql_query =
                'INSERT INTO
                     ' . self::guest_info_table . '
                (
                    ID_Student, Student_Name, Password, ID_Faculty, 
                    Academic_Year, Device_Token, Notification_Data_Version
                )
                VALUE
                (
                    :id_student, :student_name, :password, 
                    :id_faculty, :academic_year, null, 0
                )
                ON DUPLICATE KEY UPDATE ID_Student = :id_student';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':id_student' => $data['id_student'],
                    ':student_name' => $data['student_name'],
                    ':password' => $data['password'],
                    ':id_faculty' => $data['id_faculty'],
                    ':academic_year' => $data['academic_year']
                ]);

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getQLDTPassword ($id_student)
        {
            $sql_query =
                'SELECT
                    Password
                FROM
                    ' . self::guest_info_table . '
                WHERE
                    ID_Student = :id_student';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_student' => $id_student]);
                $data = $stmt->fetch(PDO::FETCH_COLUMN);

                return $data;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function updatePassword ($id_student, $qldt_password)
        {
            $sql_query =
                'UPDATE
                     ' . self::guest_info_table . '
                SET 
                    Password = :password
                WHERE
                    ID_Student = :id_student';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    'id_student' => $id_student,
                    'password' => $qldt_password
                ]);

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function upsertToken ($id_student, $token)
        {
            $sql_query =
                'UPDATE
                    ' . self::guest_info_table . '
                SET
                    Device_Token = :token
                WHERE
                    ID_Student = :id_student';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':token' => $token,
                    ':id_student' => $id_student
                ]);

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getNotificationVersion ($id_guest)
        {
            $sql_query =
                'SELECT
                    Notification_Data_Version
                FROM
                    ' . self::guest_info_table . '
                WHERE
                    ID = :id_guest';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_guest' => $id_guest]);
                $data = $stmt->fetch(PDO::FETCH_COLUMN);

                return $data;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getData ($faculty_list, $academic_year_list) : array
        {
            $sql_of_list_f =
                implode(',', array_fill(0, count($faculty_list), '?'));

            $sql_of_list_a =
                implode(',', array_fill(0, count($academic_year_list), '?'));

            $arr = array_merge($faculty_list, $academic_year_list);

            $data['device_token'] = $this->_getDeviceTokens($sql_of_list_f, $sql_of_list_a, $arr);
            $data['id_guest']     = $this->_getIDGuest($sql_of_list_f, $sql_of_list_a, $arr);

            return $data;
        }

        private function _getDeviceTokens ($sql_of_list_f, $sql_of_list_a, $arr) : array
        {
            $sql_query =
                'SELECT
                    Device_Token
                FROM
                    ' . self::guest_info_table . '
                WHERE
                    ID_Faculty IN (' . $sql_of_list_f . ') AND
                    Academic_Year IN (' . $sql_of_list_a . ')';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute($arr);
                $data = $stmt->fetchAll(PDO::FETCH_COLUMN);

                return $data;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        private function _getIDGuest ($sql_of_list_f, $sql_of_list_a, $arr) : array
        {
            $sql_query =
                'SELECT
                    ID
                FROM
                    ' . self::guest_info_table . '
                WHERE
                    ID_Faculty IN (' . $sql_of_list_f . ') AND
                    Academic_Year IN (' . $sql_of_list_a . ')';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute($arr);
                $data = $stmt->fetchAll(PDO::FETCH_COLUMN);

                return $data;

            } catch (PDOException $error) {
                throw $error;
            }
        }
    }