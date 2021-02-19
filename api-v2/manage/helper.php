<?php

    class Helper
    {
        private const participate_table = "Participate";
        private const student_table = "Student";
        private const device_table = "Device";

        private PDO $conn;
        private array $student_id_list;

        public function __construct(PDO $conn)
        {
            $this->conn = $conn;
        }

        public function getListFromModuleClassList($class_list): array
        {
            $sql_of_list = $this->_getSqlOfList($class_list);

            $this->_getListFromModuleClass($sql_of_list);

            return $this->student_id_list;
        }

        private function _getListFromModuleClass($sql): void
        {
            $sql_query =
                "SELECT
                    ID_Student
                FROM
                    " . self::participate_table . "
                WHERE
                    ID_Module_Class IN (" . $sql . ")
                ";

            $stmt = $this->conn->prepare($sql_query);
            $stmt->execute();

            $this->student_id_list = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        public function getListFromDepartmentClass($class_list): array
        {
            $sql_of_list = $this->_getSqlOfList($class_list);

            $this->_getListFromDepartmentClass($sql_of_list);

            return $this->student_id_list;
        }

        private function _getListFromDepartmentClass($sql): void
        {
            $sql_query =
                "SELECT
                    ID_Student
                FROM
                    " . self::student_table . "
                WHERE
                    ID_Class IN (" . $sql . ")
                ";

            $stmt = $this->conn->prepare($sql_query);
            $stmt->execute();

            $this->student_id_list = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        public function getTokenListFromStudentList(): array
        {
            $sql_of_list = $this->_getSqlOfList($this->student_id_list);

            $listToken = $this->_getTokenListFromStudentList($sql_of_list);

            return $listToken;
        }

        private function _getTokenListFromStudentList($sql_of_list) : array
        {
            $sql_query =
                "SELECT
                    Device_Token
                FROM
                    " . self::device_table . "
                WHERE
                    ID_Student IN (" . $sql_of_list . ")
                ";

            $stmt = $this->conn->prepare($sql_query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        private function _getSqlOfList($list): string
        {
            $sql = "";

            foreach ($list as $id) {
                $sql .= "'" . $id . "',";
            }

            $sql = rtrim($sql, ",");

            return $sql;
        }

    }
