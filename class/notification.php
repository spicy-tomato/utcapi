<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/print_error.php";

    class Notification
    {
        private const notification_table = "Notification";
        private const notification_account_table = "Notification_Account";

        private PDO $conn;
        private string $id;
        private string $title;
        private string $content;
        private string $typez;
        private string $sender;
        private array $id_account_list;
        private string $time_create;
        private ?string $time_start;
        private ?string $time_end;

        public function __construct (PDO $conn, array $info, array $id_account_list)
        {
            $this->conn            = $conn;
            $this->title           = $info['title'];
            $this->content         = $info['content'];
            $this->typez           = $info['typez'];
            $this->sender          = $info['sender'];
            $this->time_create     = $this->_getDateNow();
            $this->time_start      = $info['time_start'] != '' ? $info['time_start'] . ' 00:00:00.00' : null;
            $this->time_end        = $info['time_end'] != '' ? $info['time_end'] . ' 23:59:59.00' : null;
            $this->id_account_list = $id_account_list;
        }

        private function _getDateNow () : string
        {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $temp_date = date("d/m/Y H:i:s");

            $arr  = explode(" ", $temp_date);
            $arr2 = explode("/", $arr[0]);

            $temp_date = $arr2[2] . "/" . $arr2[1] . "/" . $arr2[0] . " " . $temp_date[1];

            return $temp_date;
        }

        public function create () : void
        {
            $sql_query = $this->_queryWithTime();

            try {
                $stmt = $this->conn->prepare($sql_query);
                $stmt->execute(array($this->title, $this->content, $this->typez, $this->sender,
                    $this->time_create, $this->time_start, $this->time_end));

                $this->id = $this->_getId();

                $this->_sendToStudent();

            } catch (PDOException $error) {
                throw $error;
            }
        }

        private function _sendToStudent () : void
        {
            $sql_query =
                "INSERT INTO
                    " . self::notification_account_table . "
                    (ID_Notification, ID_Account)
                VALUES
                    (?,?)
                ";

            $this->conn->beginTransaction();
            try {
                foreach ($this->id_account_list as $id_account) {
                    if ($id_account == null) {
                        continue;
                    }
                    $stmt = $this->conn->prepare($sql_query);
                    $stmt->execute(array($this->id, $id_account));
                }

                $this->conn->commit();

            } catch (PDOException $error) {
                $this->conn->rollBack();
                throw $error;
            }
        }

        private function _queryWithTime () : string
        {
            $sql_query =
                "INSERT INTO
                    " . self::notification_table . "
                    (Title, Content, Typez, ID_Sender, Time_Create, Time_Start, Time_End)
                VALUES
                    (?,?,?,?,?,?,?) 
                ";

            return $sql_query;
        }

        private function _getId () : string
        {
            return $this->conn->lastInsertId();
        }
    }
