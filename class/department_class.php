<?php
    include_once dirname(__DIR__) . '/shared/functions.php';

    class DepartmentClass
    {
        private const db_table = 'Class';
        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function getAll () : array
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

                $data['content'] = $stmt->fetchAll(PDO::FETCH_ASSOC);;
                $data['status_code'] = 200;

                return $data;

            } catch (PDOException $error) {
                throw $error;
            }
        }
    }