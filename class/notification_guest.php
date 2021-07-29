<?php

    class NotificationGuest
    {
        private const notification_guest_table = 'Notification_Guest';
        private const notification_account_table = 'Notification_Account';
        private const account_table = 'Account';
        private const notification_table = 'Notification';
        private const other_department_table = 'Other_Department';
        private const department_table = 'Department';
        private const faculty_table = 'Faculty';
        private const teacher_table = 'Teacher';

        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function setConnect (PDO $connect)
        {
            $this->connect = $connect;
        }

        function getAllNotification ($id_notification_list, $id_notification = '0') : array
        {

            $sql_of_list =
                implode(',', array_fill(0, count($id_notification_list), '?'));

            $id_notification_list = array_merge($id_notification_list, [$id_notification]);
            for ($i = 0; $i < 2; $i++)
            {
                $id_notification_list = array_merge($id_notification_list, $id_notification_list);
            }

            $sql_query =
                'SELECT
                        n.*,
                        od.Other_Department_Name, 
                        a.permission 
                    FROM
                        ' . self::notification_account_table . ' na,
                        ' . self::notification_table . ' n,
                        ' . self::other_department_table . ' od, 
                        ' . self::account_table . ' a  
                    WHERE
                        n.ID_Notification IN (' . $sql_of_list . ') AND
                        n.ID_Notification > ? AND
                        od.ID_Account = n.ID_Sender AND 
                        a.id = n.ID_Sender AND 
                        n.Is_Delete = 0     
                UNION
                    SELECT
                        n.*, 
                        concat(\'Khoa \', f.Faculty_Name), 
                        a.permission 
                    FROM
                        ' . self::notification_account_table . ' na,
                        ' . self::notification_table . ' n,
                        ' . self::faculty_table . ' f, 
                        ' . self::account_table . ' a    
                    WHERE
                        n.ID_Notification IN (' . $sql_of_list . ') AND
                        n.ID_Notification > ? AND
                        f.ID_Account = n.ID_Sender AND 
                        a.id = n.ID_Sender AND 
                        n.Is_Delete = 0
                UNION
                    SELECT
                        n.*, 
                        concat(\'Gv.\', t.Name_Teacher), 
                        a.permission 
                    FROM
                        ' . self::notification_account_table . ' na,
                        ' . self::notification_table . ' n,
                        ' . self::teacher_table . ' t, 
                        ' . self::account_table . ' a    
                    WHERE
                        n.ID_Notification IN (' . $sql_of_list . ') AND
                        n.ID_Notification > ? AND
                        t.ID_Account = n.ID_Sender AND 
                        a.id = n.ID_Sender AND 
                        n.Is_Delete = 0
                UNION
                    SELECT
                        n.*, 
                        d.Department_Name, 
                        a.permission 
                    FROM
                        ' . self::notification_account_table . ' na,
                        ' . self::notification_table . ' n,
                        ' . self::department_table . ' d, 
                        ' . self::account_table . ' a    
                    WHERE
                        n.ID_Notification IN (' . $sql_of_list . ') AND
                        n.ID_Notification > ? AND
                        d.ID_Account = n.ID_Sender AND 
                        a.id = n.ID_Sender AND 
                        n.Is_Delete = 0';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute($id_notification_list);
                $record = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($record)) {
                    $record = $this->_modifyResponse($record);
                }

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function insert (array $id_guest_list, string $id_notification) : void
        {
            if (empty($id_guest_list)) {
                return;
            }

            $sql_of_list =
                implode(',', array_fill(0, count($id_guest_list), '(' . $id_notification . ',?)'));

            $sql_query =
                'INSERT INTO
                    ' . self::notification_guest_table . '
                    (ID_Notification, ID_Guest)
                VALUES
                    ' . $sql_of_list;

            $this->connect->beginTransaction();
            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute($id_guest_list);

                $this->connect->commit();

            } catch (PDOException $error) {
                $this->connect->rollBack();
                throw $error;
            }
        }

        public function getIDNotification ($id_guest) : array
        {
            $sql_query =
                'SELECT
                    ID_Notification
                FROM
                    ' . self::notification_guest_table . '
                WHERE
                    ID_Guest = :id_guest';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_guest' => $id_guest]);
                $data = $stmt->fetchAll(PDO::FETCH_COLUMN);

                return $data;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        private function _modifyResponse ($arr) : array
        {
            $data = [];

            for ($i = 0; $i < count($arr); $i++) {
                $arr[$i]['ID_Notification'] = intval($arr[$i]['ID_Notification']);
                $arr[$i]['ID_Sender']       = intval($arr[$i]['ID_Sender']);
                $arr[$i]['permission']      = intval($arr[$i]['permission']);

                $data['notification'][$i] = $arr[$i];
                unset($data['notification'][$i]['Other_Department_Name']);
                unset($data['notification'][$i]['permission']);

                $data['sender'][$i]['ID_Sender']   = $arr[$i]['ID_Sender'];
                $data['sender'][$i]['Sender_Name'] = $arr[$i]['Other_Department_Name'];
                $data['sender'][$i]['permission']  = $arr[$i]['permission'];
            }

            $data['sender'] = array_values(array_unique($data['sender'], SORT_REGULAR));

            return $data;
        }

    }