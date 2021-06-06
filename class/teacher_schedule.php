<?php
    include_once dirname(__DIR__) . '/shared/functions.php';

    class TeacherSchedule
    {
        private const module_class_table = 'Module_Class';
        private const schedule_table = 'Schedules';
        private const teacher_table = 'Teacher';

        private string $teacher_id;
        private PDO $connect;

        public function __construct (PDO $connect, string $teacher_id)
        {
            $this->connect    = $connect;
            $this->teacher_id = $teacher_id;
        }

        public function getAllSchedule () : array
        {
            $sql_query =
                'SELECT
                    mdcls.Module_Class_Name, sdu.ID_Module_Class, 
                    sdu.ID_Room, sdu.Shift_Schedules, sdu.Day_Schedules
                FROM
                    ' . self::teacher_table . ' tea, 
                    ' . self::schedule_table . ' sdu,
                    ' . self::module_class_table . ' mdcls
                WHERE
                    tea.ID_Teacher = :teacher_id AND 
                    mdcls.ID_Teacher = :teacher_id AND 
                    sdu.ID_Module_Class = mdcls.ID_Module_Class 
                ORDER BY
                    sdu.Shift_Schedules';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':teacher_id' => $this->teacher_id]);
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
                $e['Shift_Schedules'] = intval($e['Shift_Schedules']);
            }

            return $data;
        }
    }