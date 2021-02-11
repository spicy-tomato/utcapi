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
			$sql = "";
			foreach ($class_list as $class_id) {
				$sql .= "'" . $class_id . "',";
			}
			$sql = rtrim($sql, ",");

			return array_unique($this->_moduleClassToStudentList($sql));
        }


		public function departmentClassToStudentList ($class_list) : array
		{
			$sql = "";
			foreach ($class_list as $class_id) {
				$sql .= "'" . $class_id . "',";
			}
			$sql = rtrim($sql, ",");

			return $this->_departmentClassToStudentList($sql);
		}

        private function _moduleClassToStudentList($sql): array
        {
            $sqlQuery =
                "SELECT
                    ID_Student
                FROM
                    " . self::participate_table . "
                WHERE
                    ID_Module_Class in (" . $sql .")
                ";

            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

		private function _departmentClassToStudentList ($sql) : array
		{
			$sqlQuery =
				"SELECT
                    ID_Student
                FROM
                    " . self::student_table . "
                WHERE
                    ID_Class in (" . $sql . ")
                ";

			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_COLUMN);
		}
    }
