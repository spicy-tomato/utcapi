<?php

    class Helper
    {
        private const participate_table = 'Participate';
        private const student_table = 'Student';
        private const device_table = 'Device';

        private PDO $connect;
        private array $student_id_list;
        private array $id_account_list;

        public function __construct(PDO $connect)
        {
            $this->connect = $connect;
        }

        public function setStudentIdList (array $student_id_list) : void
        {
            $this->student_id_list = $student_id_list;
        }

        public function getListFromModuleClassList($class_list): void
        {
            $sql_of_list = $this->_getSqlOfList($class_list);

            $this->_getListFromModuleClass($sql_of_list);
        }

        private function _getListFromModuleClass($sql_of_list): void
        {
            if ($sql_of_list == ''){
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

            $stmt = $this->connect->prepare($sql_query);
            $stmt->execute();

            $this->student_id_list = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        public function getListFromDepartmentClass($class_list)
        {
            $sql_of_list = $this->_getSqlOfList($class_list);

            $this->_getListFromDepartmentClass($sql_of_list);
        }

        private function _getListFromDepartmentClass($sql_of_list): void
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

            $stmt = $this->connect->prepare($sql_query);
            $stmt->execute();

            $this->student_id_list = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        public function getAccountListFromStudentList() : array
        {
            $sql_of_list = $this->_getSqlOfList($this->student_id_list);

            $this->_getAccountListFromStudentList($sql_of_list);

            return $this->id_account_list;
        }

        private function _getAccountListFromStudentList($sql_of_list)
        {
            $sql_query = '
                SELECT
                    ID
                FROM
                    ' . self::student_table . '
                WHERE
                    ID_Student IN (' . $sql_of_list . ')
                ';

            $stmt = $this->connect->prepare($sql_query);
            $stmt->execute();
            $this->id_account_list = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        public function getTokenListFromStudentList(): array
        {
            $sql_of_list = $this->_getSqlOfList($this->student_id_list);

            $listToken = $this->_getTokenListFromStudentList($sql_of_list);

            return $listToken;
        }

        private function _getTokenListFromStudentList($sql_of_list): array
        {
            if ($sql_of_list == '') {
                return [];
            }

            $sql_query = '
                SELECT
                    Device_Token
                FROM
                    ' . self::device_table . '
                WHERE
                    ID_Student IN (' . $sql_of_list . ')
                ';

            $stmt = $this->connect->prepare($sql_query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        private function _getSqlOfList($list): string
        {
            $sql = '';

            foreach ($list as $id) {
                $sql .= '\'' . $id . '\',';
            }

            $sql = rtrim($sql, ',');

            return $sql;
        }
    }
