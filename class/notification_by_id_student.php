<?php

    class NotificationByIDStudent
    {
        private const notification_student_table = "Notification_student";
        private const notification_table = "Notification";

        private PDO $conn;

        public function __construct(PDO $conn)
        {
            $this->conn = $conn;
        }

        public function getAll($id_student): array
        {
            $sql_query = "
                    SELECT
                        ns.ID_Notification,
                        n.*
                    FROM 
                         " . self::notification_student_table . " ns, 
                         " . self::notification_table . " n 
                    WHERE
                        ns.ID_Student = " . $_GET["ID_Student"] . " AND
                        n.ID_Notification = ns.ID_Notification
                    ";

            $stmt = $this->conn->prepare($sql_query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }