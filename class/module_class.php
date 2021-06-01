<?php
    include_once dirname(__DIR__) . '/shared/functions.php';

    class ModuleClass
    {
        private const db_table = 'Module_Class';
        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function getAll () : array
        {
            $sql_query =
                'SELECT 
                    ID_Module_Class, Module_Class_Name
                FROM 
                    ' . self::db_table . ' 
                ORDER BY
                    ID_Module_Class
                ';

            try {

                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute();

                $data['content'] = $stmt->fetchAll(PDO::FETCH_ASSOC);;
                $data['status_code'] = 200;

                return $data;

            } catch (PDOException $error) {
                printError($error);
                throw $error;
            }
        }
    }
