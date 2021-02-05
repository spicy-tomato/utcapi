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
                        ID_Class ASC
                    ";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getAllAcademic_Year(): array
        {
            $this->file = json_decode(file_get_contents("php://input"), true);
            $str        = "";
            foreach ($this->file[1] as $item) {
                $arr[] = $item;
                $str   .= "?,";
            }
            $str = rtrim($str, ",");

            $sql = "
                    SELECT 
                           Academic_Year, ID_Faculty, ID_Class 
                    FROM " . self::db_table . " 
                    WHERE
                        ID_Faculty IN (" . $str . ")
                    ORDER BY 
                        Academic_Year ASC,
                        ID_Class ASC
                    ";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($arr);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getAllFaculty(): array
        {
            $this->file = json_decode(file_get_contents("php://input"), true);
            $str        = "";
            foreach ($this->file[0] as $item) {
                $arr[] = $item;
                $str   .= "?,";
            }
            $str = rtrim($str, ",");

            $sql  = "
                    SELECT 
                           Academic_Year, ID_Faculty, ID_Class
                    FROM " . self::db_table . " 
                    WHERE 
                        Academic_Year IN (" . $str . ")
                    ORDER BY 
                        Academic_Year ASC,
                        ID_Class ASC
                    ";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($arr);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get(): array
        {
            $this->file = json_decode(file_get_contents("php://input"), true);
            $str        = ["", ""];
            $i          = 0;

            foreach ($this->file as $items) {
                foreach ($items as $item) {
                    $arr[]   = $item;
                    $str[$i] .= "?,";
                }
                $str[$i] = rtrim($str[$i], ",");
                $i++;
            }

            $sql  = "
                    SELECT 
                           Academic_Year, ID_Faculty, ID_Class 
                    FROM " . self::db_table . " 
                    WHERE 
                        Academic_Year IN (" . $str[0] . ") AND
                        ID_Faculty IN (" . $str[1] . ")
                    ORDER BY
                        Academic_Year ASC, 
                        ID_Class ASC
                    ";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($arr);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }