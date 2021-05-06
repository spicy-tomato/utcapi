<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/shared/functions.php";

    class ModuleClass
    {
        private const db_table = "Module_Class";
        private PDO $connect;

        public function __construct(PDO $connect)
        {
            $this->connect = $connect;
        }

        public function getAll()
        {
            $sql_query =
                "SELECT 
                    ID_Module_Class, Module_Class_Name
                FROM 
                    " . self::db_table . " 
                ORDER BY
                    ID_Module_Class
                ";

            try {

                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $error) {
                printError($error);

                return "Failed";
            }
        }
    }
