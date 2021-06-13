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
            $sql_query =
                'INSERT IGNORE INTO ' . self::participate_table . ' 
                (
                    ID_Module_Class, ID_Student, Process_Score, Test_Score, 
                    Theoretical_Score, Status_Studying
                ) 
                VALUES
                (
                    :id_module_class, :id_student, null, null, null, null
                )
                ON DUPLICATE KEY UPDATE
                    ID_Module_Class = :id_module_class';

            foreach ($participate_list as $participate) {
                try {
                    $stmt = $this->connect->prepare($sql_query);
                    $stmt->execute([
                        ':id_module_class' => $participate['ID_Module_Class'],
                        ':id_student' => $participate['ID_Student']
                    ]);

                } catch (PDOException $error) {
                    throw $error;
                }
            }
        }
    }