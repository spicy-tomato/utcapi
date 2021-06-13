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
                if ($this->isStudentExist($student['ID_Student'])) {
                    continue;
                }

                $student['ID_Account'] = $account->autoCreateStudentAccount($student['ID_Student'], $student['DoB']);
                $this->_insert($student);
            }
        }

        private function _insert ($student)
        {
            var_dump($student['ID_Class']);
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

        private function isStudentExist ($id_student) : bool
        {
            $sql_query =
                    'SELECT 
                        ID_Student
                    FROM 
                        ' . self::student_table . '
                    WHERE 
                        ID_Student = :id_student';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_student' => $id_student]);
                $record = $stmt->fetch(PDO::FETCH_ASSOC);

                return (bool)$record;

            } catch (PDOException $error) {
                throw $error;
            }
        }
    }
