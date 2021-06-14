<?php

    class Participate
    {
        private const participate_table = 'Participate';

        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function insert ($participate_list)
        {
            $arr         = [];
            $part_of_sql = '';
            $sql_query   =
                'INSERT INTO ' . self::participate_table . ' 
                (
                    ID_Module_Class, ID_Student, Process_Score, Test_Score, 
                    Theoretical_Score, Status_Studying
                ) 
                VALUES ';

            foreach ($participate_list as $participate) {
                $arr[] = $participate['ID_Module_Class'];
                $arr[] = $participate['ID_Student'];
                $arr[] = $participate['Process_Score'];
                $arr[] = $participate['Test_Score'];
                $arr[] = $participate['Theoretical_Score'];
                $arr[] = $participate['Status_Studying'];

                $part_of_sql .= '(?,?,?,?,?,?),';
            }
            $part_of_sql = rtrim($part_of_sql, ',');
            $sql_query   .= $part_of_sql . ' ON DUPLICATE KEY UPDATE ID_Module_Class = ID_Module_Class';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute($arr);

            } catch (PDOException $error) {
                throw $error;
            }
        }
    }