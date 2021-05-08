<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . '/shared/functions.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/utils/env_io.php';

    class Database
    {
        private string $host;
        private string $db_name;
        private string $username;
        private string $password;

        private PDO $connect;

        public function __construct ()
        {
            var_dump($_ENV);
            EnvIO::loadEnv();
            $this->host     = $_ENV['HOST'];
            $this->db_name  = $_ENV['DB_NAME'];
            $this->username = $_ENV['USER'];
            $this->password = $_ENV['PASS'];
        }

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
                $this->connect->exec('set names utf8');
            } catch (PDOException $error) {
                printError($error);

                exit(-1);
            }

            return $this->connect;
        }
    }
