<?php

    class ModuleClass
    {
        private const db_table = "module_class";
        private PDO $conn;

        public function __construct(PDO $conn)
        {
            $this->conn = $conn;
        }

        public function getAll(): array
        {
            $sql = "
                    SELECT 
                        ID_Module_Class, Module_Class_Name
                    FROM " . self::db_table . " 
                    ORDER BY
                        ID_Module_Class ASC
                    ";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }