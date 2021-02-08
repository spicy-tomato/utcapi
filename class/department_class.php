<?php

    class DepartmentClass
    {
        private const db_table = "class";
        private PDO $conn;

        public function __construct(PDO $conn)
        {
            $this->conn = $conn;
        }

        public function getAll(): array
        {
            $sql = "
                    SELECT 
                        Academic_Year, ID_Faculty, ID_Class
                    FROM " . self::db_table . " 
                    ORDER BY 
                        Academic_Year ASC,
                        ID_Faculty ASC,
                        ID_Class ASC
                    ";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }