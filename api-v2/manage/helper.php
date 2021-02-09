<?php

    class Helper
    {
        private const student_table = "Student";
        private PDO $conn;

        public function __construct(PDO $conn)
        {
            $this->conn = $conn;
        }

        public function moduleClassListToStudentList($class_list): array
        {
            $res = [];

            foreach ($class_list as $class_id) {
                $new_class_student_list = self::_moduleClassToStudentList($class_id);

                // Xem xet dung set theo goi y cua ban Hai
                $res = $res + $new_class_student_list;
            }

            return $res;
        }

        private function _moduleClassToStudentList($class_id): array
        {
            $sqlQuery =
                "SELECT
                    ID_Student
                FROM
                    " . self::student_table . "
                WHERE
                    ID_Class = :id_class
                ";

            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute([':id_class' => $class_id]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }