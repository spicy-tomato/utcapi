<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . '/shared/functions.php';

    class DepartmentClass
    {
        private const db_table = 'Class';
        private PDO $connect;

        public function __construct(PDO $connect)
        {
            $this->connect = $connect;
        }

        public function getAll()
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

                return $stmt->fetchAll(PDO::FETCH_ASSOC);


            } catch (PDOException $error) {
                printError($error);

                return 'Failed';
            }
        }
    }