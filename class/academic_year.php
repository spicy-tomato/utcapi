<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/print_error.php";

    class AcademicYear
    {
        private const db_table = "Class";
        private PDO $conn;

        public function __construct (PDO $conn)
        {
            $this->conn = $conn;
        }

        public function getAcademicYear ()
        {
            $sql_query = "
                    SELECT DISTINCT
                        Academic_Year
                    FROM " . self::db_table . " 
                    ORDER BY 
                        Academic_Year DESC 
                    LIMIT 9
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

