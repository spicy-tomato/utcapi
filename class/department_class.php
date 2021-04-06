<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/print_error.php";

    class DepartmentClass
    {
        private const db_table = "Class";
        private PDO $conn;

        public function __construct(PDO $conn)
        {
            $this->conn = $conn;
        }

        public function getAll()
        {
            $sql_query = "
                    SELECT 
                        Academic_Year, ID_Faculty, ID_Class
                    FROM " . self::db_table . " 
                    ORDER BY 
                        Academic_Year ASC,
                        ID_Faculty ASC,
                        ID_Class ASC
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