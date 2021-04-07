<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/print_error.php";

    class NotificationByIDAccount
    {
        private const notification_account_table = "Notification_Account";
        private const account_table = "Account";
        private const student_table = "Student";
        private const teacher_table = "Teacher";
        private const notification_table = "Notification";
        private const other_department_table = "Other_Department";
        private const faculty_table = "Faculty";


        private PDO $conn;

        public function __construct (PDO $conn)
        {
            $this->conn = $conn;
        }

        public function getAll ()
        {
            $id_account = $this->getIDAccount($_GET["ID"]);

            $sql_query = "
                    SELECT
                        n.*,
                        od.Other_Department_Name, 
                        a.Permission 
                    FROM
                         " . self::notification_account_table . " na,
                         " . self::notification_table . " n,
                         " . self::other_department_table . " od, 
                         " . self::account_table . " a  
                    WHERE
                        na.ID_Account = ? AND 
                        n.ID_Notification = na.ID_Notification AND
                        od.ID = n.ID_Sender AND 
                        a.id = n.ID_Sender 
                UNION
                    SELECT
                        n.*, 
                        f.Faculty_Name, 
                        a.Permission 
                    FROM
                         " . self::notification_account_table . " na,
                         " . self::notification_table . " n,
                         " . self::faculty_table . " f, 
                         " . self::account_table . " a    
                    WHERE
                        na.ID_Account = ? AND 
                        n.ID_Notification = na.ID_Notification AND
                        f.ID = n.ID_Sender AND 
                        a.id = n.ID_Sender 
                    ";

            try {
                $stmt = $this->conn->prepare($sql_query);
                $stmt->execute(array($id_account, $id_account));

                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $data = $this->modifyResponse($data);

            } catch (PDOException $e) {
                printError($e);

                $data = "Failed";
            }


            return $data;
        }

        private function getIDAccount ($id)
        {
            $sql_query = "
                    SELECT
                        a.id
                    FROM
                         " . self::student_table . " s,
                         " . self::teacher_table . " t,
                         " . self::account_table . " a  
                    WHERE
                        ((s.ID_Student = ? AND s.ID = a.id) OR
                        (t.ID_Teacher = ? AND t.ID = a.id)) 
                    ";

            try {
                $stmt = $this->conn->prepare($sql_query);
                $stmt->execute(array($id, $id));

                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return $data['id'];
            } catch (PDOException $e) {
                printError($e);

                return null;
            }
        }

        private function modifyResponse ($arr) : array
        {
            $data = [];

            for ($i = 0; $i < count($arr); $i++) {
                $arr[$i]["ID_Notification"] = intval($arr[$i]["ID_Notification"]);
                $arr[$i]["ID_Sender"]       = intval($arr[$i]["ID_Sender"]);
                $arr[$i]["Permission"]      = intval($arr[$i]["Permission"]);

                $data['notification'][$i] = $arr[$i];
                unset($data['notification'][$i]['Other_Department_Name']);
                unset($data['notification'][$i]['Permission']);

                $data['sender'][$i]['ID_Sender']   = $arr[$i]['ID_Sender'];
                $data['sender'][$i]['Sender_Name'] = $arr[$i]['Other_Department_Name'];
                $data['sender'][$i]['Permission']  = $arr[$i]['Permission'];
            }

            $data['sender'] = array_values(array_unique($data['sender'], SORT_REGULAR));

            return $data;
        }

    }