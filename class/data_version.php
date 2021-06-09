<?php

    class DataVersion
    {
        private const data_version_table = 'Data_Version';
        private const participate_table = 'Participate';
        private const module_class_table = 'Module_Class';

        private PDO $connect;
        private string $id_student;

        public function __construct (PDO $connect, $id_student = '')
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

        public function updateAllScheduleVersion ($newest_semester)
        {
            $sql_query = '
                    UPDATE ' . self::data_version_table . ' dv, 
                            (SELECT DISTINCT
                                p.ID_Student 
                            FROM ' . self::participate_table . ' p, 
                                 ' . self::module_class_table . ' mc
                            WHERE 
                                p.ID_Module_Class = mc.ID_Module_Class AND 
                                mc.School_Year = :newest_semester) temp3
                    SET Schedule = Schedule + 1
                    WHERE temp3.ID_Student = dv.ID_Student
                    ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':newest_semester' => $newest_semester]);

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
