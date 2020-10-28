<?php

    class CSDL
    
        private string $host     = 'sql106.epizy.com';
        private string $db_name  = 'epiz_27035084_qldt';
        private string $username = 'epiz_27035084';
        private string $password = '4AKOBXoarp';

        public PDO $ket_noi;

        public function __construct (){ }

        public function KetNoi () : PDO
        {
            try {
                $this->ket_noi = new PDO(
                    "mysql:host=" . $this->host .
                    ";dbname=" . $this->db_name,
                    $this->username,
                    $this->password
                );
                $this->ket_noi->exec("set names utf8");
            }
            catch (PDOException $loi){
                echo "Database could not be connected: " . $loi->getMessage();
            }

            return $this->ket_noi;
        }
    }
