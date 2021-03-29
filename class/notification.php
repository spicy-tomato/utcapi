<?php

    class Notification
    {
        private const notification_table = "Notification";
        private const notification_student_table = "Notification_Student";

        private PDO $conn;
        private string $id;
        private string $title;
        private string $content;
        private string $typez;
        private string $sender;
        private array $student_list;
        private ?DateTime $time_start = null;
        private ?DateTime $time_end = null;
        private ?DateTime $expired = null;

        public function __construct(PDO $conn, array $info, array $student_list)
        {
            $this->conn         = $conn;
            $this->title        = $info['title'];
            $this->content      = $info['content'];
            $this->typez        = $info['typez'];
            $this->sender       = $info['sender'];
            $this->student_list = $student_list;
        }

        public function setTime(array $time): void
        {
            $this->time_start = $time['time_start'];
            $this->time_end   = $time['time_end'];
            $this->expired    = $time['expired'];
        }

        public function create(): void
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

                $this->_sendToStudent($this->student_list);

            } catch (PDOException $error) {
                throw $error;
            }
        }

        private function _sendToStudent(array $studentList): void
        {
            $sqlQuery =
                "INSERT INTO
                    " . self::notification_student_table . "
                    (ID_Notification, ID_Student)
                VALUES
                    (:notification_id, :student_id)
                ";

            $this->conn->beginTransaction();

            try {
                foreach ($studentList as $student_id) {
                    $stmt = $this->conn->prepare($sqlQuery);
                    $stmt->execute([
                        ':notification_id' => $this->id,
                        ':student_id' => $student_id
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
                    ('{$this->title}', '{$this->content}', '{$this->typez}', '{$this->sender}', 
                    '{$this->time_start}', '{$this->time_end}', '{$this->expired}') 
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
                    ('{$this->title}', '{$this->content}', '{$this->typez}', '{$this->sender}')
                ";

            return $sqlQuery;
        }

        private function _getId(): string
        {
            return $this->conn->lastInsertId();
        }
    }
