<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . "/shared/functions.php";

    class Database
    {
        private string $host     = 'frwahxxknm9kwy6c.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
        private string $db_name  = 'ns46ojgfb89b7zve';
        private string $username = 'ha3knlk2xkvximtp';
        private string $password = 'k1wjbrghzjdjasg4';

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
