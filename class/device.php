<?php
    include_once dirname(__DIR__) . '/shared/functions.php';

    class Device
    {
        private const device_table = 'Device';

        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function deleteOldToken($old_token)
        {
            $sql_query = '
            DELETE 
            FROM ' . self::device_table . '
            WHERE Device_Token = :old_token';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':old_token' => $old_token]);

            } catch (PDOException $error)
            {
                printError($error);
            }
        }
    }