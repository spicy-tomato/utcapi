<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . "/shared/functions.php";

    class Database
    {
        private string $host     = 'localhost';
        private string $db_name  = 'nckh3';
        private string $username = 'WRBKOR23';
        private string $password = 'hai210501';

        private PDO $connect;

        public function __construct (){ }

        public function connect () : PDO
        {
            try {
                $this->connect = new PDO(
                    "mysql:charset=utf8mb4;
                    host=$this->host;
                    dbname=$this->db_name",
                    $this->username,
                    $this->password
                );
                $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connect->exec("set names utf8");
            }
            catch (PDOException $error){
                printError($error);

                exit(-1);
            }

            return $this->connect;
        }
    }
