<?php

    class ModuleClass
    {
        private const db_table = "class";
        private PDO $conn;

        public function __construct(PDO $conn)
        {
            $this->$conn = $conn;
        }


    }