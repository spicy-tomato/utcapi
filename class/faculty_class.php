<?php
    include_once dirname(__DIR__) . '/shared/functions.php';

    class FacultyClass
    {
        private const db_table = 'Class';
        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function getAllFacultyClass () : array
        {
            $sql_query = '
                    SELECT 
                        Academic_Year, ID_Faculty, ID_Class
                    FROM ' . self::db_table . '  
                    ORDER BY 
                        Academic_Year ASC,
                        ID_Faculty ASC,
                        ID_Class ASC
                    ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute();
                $record = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getAcademicYear () : array
        {
            $sql_query = '
                    SELECT DISTINCT
                        Academic_Year
                    FROM ' . self::db_table . ' 
                    ORDER BY 
                        Academic_Year DESC 
                    LIMIT 9
                    ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute();
                $record = $stmt->fetchAll(PDO::FETCH_ASSOC);;

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }
    }
