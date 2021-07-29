<?php

    include_once 'account.php';

    class Student
    {
        private const student_table = 'Student';

        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function insert ($sql_data, $part_of_sql)
        {
            $this->_insert($part_of_sql, $sql_data);
        }

        private function _insert ($part_of_sql, $sql_data)
        {
            $sql_query =
                'INSERT INTO 
                    ' . self::student_table . ' 
                (
                    ID_Student, Student_Name, DoB_Student, ID_Class, 
                    ID_Card_Number, Phone_Number_Student, Address_Student
                ) 
                VALUES 
                    ' . $part_of_sql . '
                ON DUPLICATE KEY UPDATE ID_Student = ID_Student';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute($sql_data);

            } catch (PDOException $error) {
                throw $error;
            }
        }
    }