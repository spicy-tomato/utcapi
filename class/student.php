<?php

    include_once 'account.php';
    include_once 'data_version.php';
    include_once 'faculty_class.php';

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
            $account       = new Account($this->connect);
            $faculty_class = new FacultyClass($this->connect);
            $data_version  = new DataVersion($this->connect);

            $id_student_list = [];

            foreach ($student_list as &$student) {
                try {
                    $this->_insert($student);
                    $account->autoCreateStudentAccount($student['ID_Student'], $student['DoB']);
                    $id_student_list[] = $student['ID_Student'];

                } catch (PDOException $error) {
                    if ($error->getCode() == 23000 &&
                        $error->errorInfo[1] == 1062) {

                        continue;
                    }
                    if ($error->getCode() == 23000 &&
                        $error->errorInfo[1] == 1452) {

                        $faculty_class->insert($student['ID_Class']);
                        $student_list[] = $student;
                        continue;
                    }
                    throw $error;
                }
            }

            $account->bindIDAccountToStudent();
            $data_version->insert($id_student_list);
        }

        private function _insert ($student)
        {
            $sql_query =
                'INSERT INTO ' . self::student_table . ' 
                (
                    ID_Student, Student_Name, DoB_Student, ID_Class, 
                    ID_Card_Number, Phone_Number_Student, Address_Student
                ) 
                VALUES
                (
                    :id_student, :student_name, :dob, 
                    :id_class, null, null, null
                )';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':id_student' => $student['ID_Student'],
                    ':student_name' => $student['Student_Name'],
                    ':dob' => $student['DoB'],
                    ':id_class' => $student['ID_Class']
                ]);

            } catch (PDOException $error) {
                throw $error;
            }
        }
    }
