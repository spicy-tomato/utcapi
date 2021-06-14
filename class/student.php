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

        public function insert ($student_list)
        {
            $account = new Account($this->connect);

            foreach ($student_list as $student) {
                try {
                    $student['ID_Account'] = $account->autoCreateStudentAccount($student['ID_Student'], $student['DoB']);
                    $this->_insert($student);

                } catch (PDOException $error) {
                    if ($error->getCode() == 23000) {
                        continue;
                    }
                    throw $error;
                }
            }
        }

        private function _insert ($student)
        {
            $sql_query =
                'INSERT IGNORE INTO ' . self::student_table . ' 
                (
                    ID_Student, Student_Name, DoB_Student, ID_Class, 
                    ID_Card_Number, Phone_Number_Student, Address_Student, ID_Account 
                ) 
                VALUES
                (
                    :id_student, :student_name, :dob, :id_class,
                    null, null, null, :id_account
                )';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':id_student' => $student['ID_Student'],
                    ':student_name' => $student['Student_Name'],
                    ':dob' => $student['DoB'],
                    ':id_class' => $student['ID_Class'],
                    ':id_account' => $student['ID_Account']
                ]);

            } catch (PDOException $error) {
                throw $error;
            }
        }
    }
