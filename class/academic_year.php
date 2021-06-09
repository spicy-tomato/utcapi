<?php

    class AcademicYear
    {
        private const db_table = 'Class';
        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
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

                $data['content'] = $stmt->fetchAll(PDO::FETCH_ASSOC);;
                $data['status_code'] = 200;

                return $data;

            } catch (PDOException $error) {
                throw $error;
            }
        }
    }

