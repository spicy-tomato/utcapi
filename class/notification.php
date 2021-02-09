<?php

    class Notification
    {
        private const notification_table = "Notification";
        private const notfication_student_table = "Notification_Student";

        private PDO $conn;
        private string $id;
        private string $title;
        private string $content;
        private string $typez;
        private string $sender;
        private ?DateTime $time_start = null;
        private ?DateTime $time_end = null;
        private ?DateTime $expired = null;

        public function __construct(PDO $conn, string $title, string $content, string $typez, string $sender)
        {
            $this->conn    = $conn;
            $this->title   = $title;
            $this->content = $content;
            $this->typez   = $typez;
            $this->sender  = $sender;
        }

        public function setTime(DateTime $time_start, DateTime $time_end, DateTime $expired)
        {
            $this->time_start = $time_start;
            $this->time_end   = $time_end;
            $this->expired    = $expired;
        }

        public function create(): array
        {
            $sqlQuery = null;
            if ($this->time_start === null && $this->time_end === null && $this->expired === null) {
                $sqlQuery = $this->_queryWithoutTime();
            }
            else {
                $sqlQuery = $this->_queryWithTime();
            }

            try {
                $stmt = $this->conn->prepare($sqlQuery);
                $stmt->execute();

                $this->id = $this->_getId();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $error) {
                exit($error->getMessage());
            }
        }

        public function sendToStudent(array $studentList)
        {
            $sqlQuery =
                "INSERT INTO
                    " . self::notfication_student_table . "
                    (ID_Notification, ID_Student)
                VALUES
                    (:notification_id, :student_id)
                ";

            try {
                $this->conn->beginTransaction();

                foreach ($studentList as $student_id) {
                    $stmt = $this->conn->prepare($sqlQuery);
                    $stmt->execute([
                        'notification_id' => $this->id,
                        'student_id' => $student_id
                    ]);
                }

                $this->conn->commit();

            } catch (PDOException $error) {
                $this->conn->rollBack();
                throw $error;
            }
        }

        private function _queryWithTime(): string
        {
            $sqlQuery =
                "INSERT INTO
                    " . self::notification_table . "
                    (Title, Content, Typez, Sender, Time_Start, Time_End, Expired)
                VALUES
                    ({$this->title}, {$this->content}, {$this->typez}, {$this->sender}, {$this->time_start}, {$this->time_end}, {$this->expired}) 
                ";

            return $sqlQuery;
        }

        private function _queryWithoutTime(): string
        {
            $sqlQuery =
                "INSERT INTO
                    " . self::notification_table . "
                    (Title, Content, Typez, Sender)
                VALUES
                    ({$this->title}, {$this->content}, {$this->typez}, {$this->sender})
                ";

            return $sqlQuery;
        }

        private function _getId(): string
        {
            $sqlQuery = "SELECT LAST_INSERT_ID()";

            $stmt = $this->conn->prepare($sqlQuery);
            return $stmt->execute();
        }
    }
