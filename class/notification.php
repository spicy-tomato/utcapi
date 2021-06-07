<?php
    include_once dirname(__DIR__) . '/shared/functions.php';

    class Notification
    {
        private const notification_table = 'Notification';
        private const notification_account_table = 'Notification_Account';

        private PDO $connect;
        private string $title;
        private string $content;
        private string $typez;
        private string $sender;
        private string $time_create;
        private ?string $time_start;
        private ?string $time_end;

        public function __construct (PDO $connect, array $info)
        {
            $this->connect         = $connect;
            $this->title           = $info['title'];
            $this->content         = $info['content'];
            $this->typez           = $info['typez'];
            $this->sender          = $info['sender'];
            $this->time_create     = $this->_getDateNow();
            $this->time_start      = $info['time_start'] != '' ? $info['time_start'] . ' 00:00:00.00' : null;
            $this->time_end        = $info['time_end'] != '' ? $info['time_end'] . ' 23:59:59.00' : null;
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

        public function create () : string
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

        private function _getIdNotification () : string
        {
            return $this->connect->lastInsertId();
        }
    }
