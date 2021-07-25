<?php

    class DataVersionTeacher
    {
        private const data_version_teacher_table = 'Data_Version_Teacher';

        private PDO $connect;
        private string $id_teacher;

        public function __construct (PDO $connect, $id_teacher = '')
        {
            $this->connect    = $connect;
            $this->id_teacher = $id_teacher;
        }

        public function getDataVersion ($type) : int
        {
            $sql_query =
                'SELECT
                    ' . $type . '
                FROM
                    ' . self::data_version_teacher_table . '
                WHERE
                    ID_Teacher = :id_teacher';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_teacher' => $this->id_teacher]);
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
                    Schedule
                FROM
                    ' . self::data_version_teacher_table . '
                WHERE
                    ID_Teacher = :id_teacher';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_teacher' => $this->id_teacher]);
                $record = $stmt->fetch(PDO::FETCH_ASSOC);
                $record = $this->_formatResponse($record);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        private function _formatResponse (&$data)
        {
            $data['Schedule'] = intval($data['Schedule']);

            return $data;
        }
    }