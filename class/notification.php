<?php

    class Notification
    {
        private const notification_table = 'Notification';

        private PDO $connect;
        private string $title;
        private string $content;
        private string $typez;
        private string $sender;
        private string $time_create;
        private ?string $time_start;
        private ?string $time_end;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function setUpData (array $info)
        {
            $this->title       = $info['title'];
            $this->content     = $info['content'];
            $this->typez       = $info['typez'];
            $this->sender      = $info['sender'];
            $this->time_create = $this->_getDateNow();
            $this->time_start  = $info['time_start'] != '' ? $info['time_start'] . ' 00:00:00.00' : null;
            $this->time_end    = $info['time_end'] != '' ? $info['time_end'] . ' 23:59:59.00' : null;
        }

        private function _getDateNow () : string
        {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $temp_date = date('d/m/Y H:i:s');

            $arr  = explode(' ', $temp_date);
            $arr2 = explode('/', $arr[0]);

            $temp_date = $arr2[2] . '/' . $arr2[1] . '/' . $arr2[0] . ' ' . $arr[1];

            return $temp_date;
        }

        public function insert () : string
        {
            $sql_query =
                'INSERT INTO
                    ' . self::notification_table . '
                    (Title, Content, Typez, ID_Sender, 
                    Time_Create, Time_Start, Time_End)
                VALUES
                    (:title, :content, :typez, :id_sender, 
                    :time_create, :time_start, :time_end) 
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':title' => $this->title,
                    ':content' => $this->content,
                    ':typez' => $this->typez,
                    ':id_sender' => $this->sender,
                    ':time_create' => $this->time_create,
                    ':time_start' => $this->time_start,
                    ':time_end' => $this->time_end]);

                $id_notification = $this->_getIdNotification();

                return $id_notification;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getNotificationForSender ($id_sender, $num) : array
        {
            $sql_query =
                'SELECT
                    ID_Notification, Title, Content, 
                    Time_Create, IFNULL(Time_Start, \'\') Time_Start, 
                    IFNULL(Time_End, \'\') Time_End
                FROM
                    ' . self::notification_table . '
                WHERE  
                    ID_Sender = :id_sender AND
                    Is_Delete != 1
                ORDER BY 
                    ID_Notification DESC
                LIMIT 
                    ' . $num . ',15';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_sender' => $id_sender]);
                $record = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function setDeleteNotification ($index_arr)
        {
            $sql_of_list = implode(',', array_fill(0, count($index_arr), '?'));

            $sql_query =
                'UPDATE
                    ' . self::notification_table . '
                SET
                    Is_Delete = 1
                WHERE  
                    ID_Notification IN (' . $sql_of_list . ')';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute($index_arr);

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getDeletedNotification () : array
        {
            $sql_query = '
                SELECT
                    ID_Notification
                FROM
                    ' . self::notification_table . '
                WHERE
                    Is_Delete = 1 AND
                    Time_Create >= DATE_SUB(NOW(), INTERVAL 3 WEEK )
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute();
                $record = $stmt->fetchAll(PDO::FETCH_COLUMN);

                array_walk($record, 'intval');
                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        private function _getIdNotification () : string
        {
            return $this->connect->lastInsertId();
        }
    }
