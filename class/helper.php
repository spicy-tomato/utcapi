<?php

    class Helper
    {
        private const participate_table = 'Participate';
        private const student_table = 'Student';

        private PDO $connect;
        private array $id_student_list;
        private array $id_account_list;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function setIdStudentList (array $student_id_list) : void
        {
            $this->id_student_list = $student_id_list;
        }

        public function getIdStudentList () : array
        {
            return $this->id_student_list;
        }

        public function getListFromModuleClassList ($class_list) : void
        {
            $sql_of_list = $this->_getSqlOfList($class_list);

            $this->_getListFromModuleClass($sql_of_list);
        }

        private function _getListFromModuleClass ($sql_of_list) : void
        {
            if ($sql_of_list == '') {
                return;
            }

            $sql_query = '
                SELECT
                    ID_Student
                FROM
                    ' . self::participate_table . '
                WHERE
                    ID_Module_Class IN (' . $sql_of_list . ')
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute();
                $record = $stmt->fetchAll(PDO::FETCH_COLUMN);

                $this->id_student_list = $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getListFromDepartmentClass ($class_list)
        {
            $sql_of_list = $this->_getSqlOfList($class_list);

            $this->_getListFromDepartmentClass($sql_of_list);
        }

        private function _getListFromDepartmentClass ($sql_of_list) : void
        {
            if ($sql_of_list == '') {
                return;
            }

            $sql_query =
                'SELECT
                    ID_Student
                FROM
                    ' . self::student_table . '
                WHERE
                    ID_Class IN (' . $sql_of_list . ')
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute();
                $record = $stmt->fetchAll(PDO::FETCH_COLUMN);

                $this->id_student_list = $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getAccountListFromStudentList () : array
        {
            $sql_of_list = $this->_getSqlOfList($this->id_student_list);

            $this->_getAccountListFromStudentList($sql_of_list);

            return $this->id_account_list;
        }

        private function _getAccountListFromStudentList ($sql_of_list)
        {
            $sql_query = '
                SELECT
                    ID
                FROM
                    ' . self::student_table . '
                WHERE
                    ID_Student IN (' . $sql_of_list . ')
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute();
                $record = $stmt->fetchAll(PDO::FETCH_COLUMN);

                $this->id_account_list = $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        private function _getSqlOfList ($list) : string
        {
            $part_of_sql = '';

            foreach ($list as $id) {
                $part_of_sql .= '\'' . $id . '\',';
            }

            $part_of_sql = rtrim($part_of_sql, ',');

            return $part_of_sql;
        }
    }
