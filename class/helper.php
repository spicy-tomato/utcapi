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
            $this->_getListFromModuleClass($class_list);
        }

        private function _getListFromModuleClass ($class_list) : void
        {
            $sql_query_1 = '
                CREATE TEMPORARY TABLE temp (
                  ID_Module_Class varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
                ';

            $sql_of_list =
                implode(',', array_fill(0, count($class_list), '(?)'));

            $sql_query_2 =
                'INSERT INTO temp
                    (ID_Module_Class)
                VALUES
                    ' . $sql_of_list;

            $sql_query_3 = '
                SELECT 
                    ID_Student
                FROM 
                     temp t 
                         LEFT OUTER JOIN
                     ' . self::participate_table . ' p
                        ON t.ID_Module_Class = p.ID_Module_Class
                ';

            try {
                $stmt = $this->connect->prepare($sql_query_1);
                $stmt->execute();

                $stmt = $this->connect->prepare($sql_query_2);
                $stmt->execute($class_list);

                $stmt = $this->connect->prepare($sql_query_3);
                $stmt->execute();
                $record                = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $this->id_student_list = $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getListFromDepartmentClass ($class_list)
        {
            $this->_getListFromDepartmentClass($class_list);
        }

        private function _getListFromDepartmentClass ($class_list) : void
        {
            $sql_query_1 = '
                CREATE TEMPORARY TABLE temp (
                  ID_Class varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
                ';

            $sql_of_list =
                implode(',', array_fill(0, count($class_list), '(?)'));

            $sql_query_2 =
                'INSERT INTO temp
                    (ID_Class)
                VALUES
                    ' . $sql_of_list;

            $sql_query_3 = '
                SELECT 
                    ID_Student
                FROM 
                     temp t 
                         LEFT OUTER JOIN
                     ' . self::student_table . ' s
                        ON t.ID_Class = s.ID_Class
                ';

            try {
                $stmt = $this->connect->prepare($sql_query_1);
                $stmt->execute();

                $stmt = $this->connect->prepare($sql_query_2);
                $stmt->execute($class_list);

                $stmt = $this->connect->prepare($sql_query_3);
                $stmt->execute();
                $record                = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $this->id_student_list = $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getAccountListFromStudentList () : array
        {
            $this->_getAccountListFromStudentList();

            return $this->id_account_list;
        }

        private function _getAccountListFromStudentList ()
        {
            if (empty($this->id_student_list[0])) {
                $this->id_account_list = [];
                return;
            }

            $sql_query_1 = '
                CREATE TEMPORARY TABLE temp1 (
                  ID_Student varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
                ';

            $sql_of_list =
                implode(',', array_fill(0, count($this->id_student_list), '(?)'));

            $sql_query_2 =
                'INSERT INTO temp1
                    (ID_Student)
                VALUES
                    ' . $sql_of_list;

            $sql_query_3 = '
                SELECT 
                    ID_Account
                FROM 
                     temp1 t 
                         LEFT OUTER JOIN
                     ' . self::student_table . ' s
                        ON t.ID_Student = s.ID_Student
                WHERE
                    ID_Account IS NOT NULL
                ';

            try {
                $stmt = $this->connect->prepare($sql_query_1);
                $stmt->execute();

                $stmt = $this->connect->prepare($sql_query_2);
                $stmt->execute($this->id_student_list);

                $stmt = $this->connect->prepare($sql_query_3);
                $stmt->execute();
                $record                = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $this->id_account_list = $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }
    }
