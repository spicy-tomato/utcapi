<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/print_error.php";

    class ModuleClass
    {
        private const db_table = "Module_Class";
        private PDO $conn;

        public function __construct(PDO $conn)
        {
            $this->conn = $conn;
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

                $stmt = $this->conn->prepare($sql_query);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                printError($e);

                return "Failed";
            }
        }
    }