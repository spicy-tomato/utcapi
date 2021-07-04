<?php

    class Participate
    {
        private const participate_table = 'Participate';

        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function insert ($participate_list) : bool
        {
            $arr         = [];
            $part_of_sql = '';

            foreach ($participate_list as $participate) {
                $arr[] = $participate['ID_Module_Class'];
                $arr[] = $participate['ID_Student'];

                $part_of_sql .= '(?,?,null,null,null,null),';
            }
            $part_of_sql = rtrim($part_of_sql, ',');

            $sql_query =
                'INSERT INTO ' . self::participate_table . ' 
                (
                    ID_Module_Class, ID_Student, Process_Score, Test_Score, 
                    Theoretical_Score, Status_Studying
                ) 
                VALUES 
                    ' . $part_of_sql . '
                ON DUPLICATE KEY UPDATE ID_Module_Class = ID_Module_Class';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute($arr);

                return true;

            } catch (PDOException $error) {
                if ($error->getCode() == 23000 &&
                    $error->errorInfo[1] == 1452) {

                    return false;
                }
                throw $error;
            }
        }
    }