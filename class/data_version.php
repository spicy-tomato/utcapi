<?php

    class DataVersion
    {
        private const data_version_table = 'Data_Version';

        private PDO $connect;
        private string $id_student;

        public function __construct (PDO $connect, $id_student)
        {
            $this->connect    = $connect;
            $this->id_student = $id_student;
        }

        public function updateDataVersion ($type)
        {
            $sql_query = '
                    UPDATE
                        ' . self::data_version_table . ' 
                    SET
                        ' . $type . ' = ' . $type . ' + 1
                    WHERE
                        ID_Student = :id_student
                    ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_student' => $this->id_student]);

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getDataVersion ($type) : int
        {
            $sql_query =
                'SELECT
                    ' . $type . '
                FROM
                    ' . self::data_version_table . '
                WHERE
                    ID_Student = :id_student
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_student' => $this->id_student]);
                $record = $stmt->fetch(PDO::FETCH_COLUMN);

                return intval($record);

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getAllDataVersion ()
        {
            $sql_query =
                'SELECT
                    Schedule, Notification, Exam_Schedule, Module_Score
                FROM
                    ' . self::data_version_table . '
                WHERE
                    ID_Student = :id_student
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_student' => $this->id_student]);
                $record = $stmt->fetch(PDO::FETCH_ASSOC);
                $record = $this->_formatResponse($record);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        private function _formatResponse (&$data)
        {
            $data['Schedule']      = intval($data['Schedule']);
            $data['Notification']  = intval($data['Notification']);
            $data['Exam_Schedule'] = intval($data['Exam_Schedule']);
            $data['Module_Score']  = intval($data['Module_Score']);

            return $data;
        }
    }
