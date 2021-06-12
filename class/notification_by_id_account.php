<?php

    class NotificationByIDAccount
    {
        private const notification_account_table = 'Notification_Account';
        private const account_table = 'Account';
        private const notification_table = 'Notification';
        private const notification_delete_table = 'Notification_Delete';
        private const other_department_table = 'Other_Department';
        private const department_table = 'Department';
        private const faculty_table = 'Faculty';
        private const teacher_table = 'Teacher';

        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function getAllNotification ($id_account, $id_notification = '1') : array
        {
            $sql_query = '
                    SELECT
                        n.*,
                        od.Other_Department_Name, 
                        a.permission 
                    FROM
                         ' . self::notification_account_table . ' na,
                         ' . self::notification_table . ' n,
                         ' . self::other_department_table . ' od, 
                         ' . self::account_table . ' a  
                    WHERE
                        na.ID_Account = :id_account AND 
                        n.ID_Notification > :id_notification AND
                        n.ID_Notification = na.ID_Notification AND
                        od.ID_Account = n.ID_Sender AND 
                        a.id = n.ID_Sender 
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
                        na.ID_Account = :id_account AND 
                        n.ID_Notification > :id_notification AND
                        n.ID_Notification = na.ID_Notification AND
                        f.ID_Account = n.ID_Sender AND 
                        a.id = n.ID_Sender 
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
                        na.ID_Account = :id_account AND 
                        n.ID_Notification > :id_notification AND
                        n.ID_Notification = na.ID_Notification AND
                        t.ID_Account = n.ID_Sender AND 
                        a.id = n.ID_Sender
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
                        na.ID_Account = :id_account AND 
                        n.ID_Notification > :id_notification AND
                        n.ID_Notification = na.ID_Notification AND
                        d.ID_Account = n.ID_Sender AND 
                        a.id = n.ID_Sender 
                    ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':id_account' => $id_account,
                    ':id_notification' => $id_notification
                ]);
                $record = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($record)) {
                    $record = $this->_modifyResponse($record);
                }

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getDeletedNotification() : array
        {
            $sql_query = '
                SELECT
                    ID_Notification
                FROM
                    ' . self::notification_delete_table . ' nd
                WHERE
                    nd.Time_Delete >= DATE_SUB(NOW(), INTERVAL 3 WEEK )
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute();
                $record = $stmt->fetchAll(PDO::FETCH_COLUMN);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function pushData (array $id_account_list, string $id_notification) : void
        {
            if (empty($id_account_list))
            {
                return;
            }

            $sql_of_list =
                implode(',', array_fill(0, count($id_account_list), '(' . $id_notification . ',?)'));

            $sql_query =
                'INSERT INTO
                    ' . self::notification_account_table . '
                    (ID_Notification, ID_Account)
                VALUES
                    ' . $sql_of_list;

            $this->connect->beginTransaction();
            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute($id_account_list);

                $this->connect->commit();

            } catch (PDOException $error) {
                $this->connect->rollBack();
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