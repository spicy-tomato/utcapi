<?php

    class Helper
    {
        private const participate_table = "Participate";
		private const student_table = "student";
		private PDO $conn;

        public function __construct(PDO $conn)
        {
            $this->conn = $conn;
        }

        public function moduleClassListToStudentList($class_list): array
        {
            $res = [];

            foreach ($class_list as $class_id) {
                $new_class_student_list = $this->_moduleClassToStudentList($class_id);
				$res = array_merge($res, $new_class_student_list);
            }

//            var_dump($res);
//            echo json_encode(array_unique($res));
            return array_unique($res);
        }


		public function departmentClassToStudentList ($class_list) : array
		{
			$res = [];

			foreach ($class_list as $class_id) {
				$new_class_student_list = $this->_departmentClassToStudentList($class_id);
				$res = array_merge($res, $new_class_student_list);

			}

			return $res;
		}

        private function _moduleClassToStudentList($class_id): array
        {
            $sqlQuery =
                "SELECT
                    ID_Student
                FROM
                    " . self::participate_table . "
                WHERE
                    ID_Module_Class = :id_class
                ";

            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute([':id_class' => $class_id]);

            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

		private function _departmentClassToStudentList ($class_id) : array
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

			return $stmt->fetchAll(PDO::FETCH_COLUMN);
		}
    }
