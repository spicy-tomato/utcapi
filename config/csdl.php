<?php

    class CSDL
    {
        private string $host     = 'localhost';
        private string $db_name  = 'nckh';
        private string $username = 'root';
        private string $password = 'hai210501';

        public PDO $ket_noi;

        public function __construct (){ }

        public function KetNoi () : PDO
        {
            try {
                $this->ket_noi = new PDO(
                    "mysql:charset=utf8mb4;
                    host=$this->host;
                    dbname=$this->db_name",
                    $this->username,
                    $this->password
                );
                $this->ket_noi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->ket_noi->exec("set names utf8");
            }
            catch (PDOException $loi){
                echo "Database could not be connected: " . $loi->getMessage();
            }

            return $this->ket_noi;
        }
    }
