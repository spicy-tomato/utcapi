<?php

    class StudentSchedule
    {
        private const module_class_table = 'Module_Class';
        private const teacher_table = 'Teacher';
        private const schedule_table = 'Schedules';
        private const participate_table = 'Participate';

        private string $student_id;
        private PDO $connect;

        public function __construct (PDO $connect, string $student_id)
        {
            $this->connect    = $connect;
            $this->student_id = $student_id;
        }

        public function getAllSchedule () : array
        {
            $sql_query =
                'SELECT
                    sdu.ID_Schedules, mc_t.Module_Class_Name, sdu.ID_Module_Class, 
                    sdu.ID_Room, sdu.Shift_Schedules, sdu.Day_Schedules, mc_t.Name_Teacher
                FROM
                    ' . self::schedule_table . ' sdu,
                    ' . self::participate_table . ' par,
                    (SELECT 
                        ID_Module_Class, Module_Class_Name, Name_Teacher
                    FROM 
                        '.self::module_class_table .' mc
                            LEFT JOIN 
                        '.self::teacher_table .' t
                        ON mc.ID_Teacher = t.ID_Teacher) mc_t
                WHERE
                    par.ID_Student = :id_student AND
                    sdu.ID_Module_Class = par.ID_Module_Class AND
                    mc_t.ID_Module_Class = sdu.ID_Module_Class AND
                    sdu.Day_Schedules >= DATE_SUB(NOW(), INTERVAL 1 YEAR)
                ORDER BY
                    sdu.Shift_Schedules';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_student' => $this->student_id]);
                $record = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($record)) {
                    $record = $this->_formatResponse($record);
                }

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        private function _formatResponse ($data)
        {
            foreach ($data as &$e) {
                $e['ID_Schedules']    = intval($e['ID_Schedules']);
                $e['Shift_Schedules'] = intval($e['Shift_Schedules']);
            }

            return $data;
        }
    }
