<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/print_error.php";

    class AcademicYear
    {
        private const db_table = "Class";
        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
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
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $error) {
                printError($error);

                return "Failed";
            }
        }
    }

