<?php

    class DataVersion
    {
        private const data_version_table = 'Data_Version';

        private PDO $connect;
        private string $id_student = '';

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
    }
