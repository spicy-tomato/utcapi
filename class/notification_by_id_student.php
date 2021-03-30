<?php

    class NotificationByIDStudent
    {
        private const notification_student_table = "Notification_Student";
        private const notification_table = "Notification";
        private const account_table = "Account";
        private const other_department_table = "Other_Department";


        private PDO $conn;

        public function __construct(PDO $conn)
        {
            $this->conn = $conn;
        }

        public function getAll(): array
        {
            $sql_query = "
                    SELECT
                        ns.ID_Notification,
                        n.*,
                       
                    FROM 
                         " . self::notification_student_table . " ns, 
                         " . self::notification_table . " n 
                    WHERE
                        ns.ID_Student = " . $_GET["ID_Student"] . " AND
                        n.ID_Notification = ns.ID_Notification
                    ";

            $stmt = $this->conn->prepare($sql_query);
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data = $this->convertID($data);

            return $data;
        }

        private function convertID($data)
        {
            for ($i = 0; $i < count($data); $i++)
            {
                $data[$i]["ID_Notification"] = intval($data[$i]["ID_Notification"]);
            }

            return $data;
        }
    }