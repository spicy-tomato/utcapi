<?php

    class NotificationDelete
    {
        private const notification_delete_table = 'Notification_Delete';

        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function insert ($index_arr)
        {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $current_time = date('Y-m-d H:i:s');

            $sql_of_list = implode(',', array_fill(0, count($index_arr), '(?,\'' . $current_time . '\')'));

            $sql_query = '
                INSERT INTO 
                     ' . self::notification_delete_table . '
                    (ID_Notification, Time_Delete)
                VALUES ' . $sql_of_list;

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute($index_arr);

            } catch (PDOException $error) {
                throw $error;
            }
        }
    }