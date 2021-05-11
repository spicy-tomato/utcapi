<?php


    include_once dirname(__DIR__) . '/shared/functions.php';

    class TeacherSchedule
    {
        private const module_table = 'Module';
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

        public function getAll ()
        {
            $sql_query =
                'SELECT
                    mdcls.Module_Class_Name, sdu.ID_Module_Class, 
                    sdu.ID_Room, sdu.Shift_Schedules, sdu.Day_Schedules
                FROM
                    ' . self::teacher_table . ' tea, 
                    ' . self::module_table . ' mc,
                    ' . self::schedule_table . ' sdu,
                    ' . self::module_class_table . ' mdcls
                WHERE
                    tea.ID_Teacher = :teacher_id AND 
                    mdcls.ID_Teacher = :teacher_id AND 
                    mc.ID_Module = mdcls.ID_Module AND
                    sdu.ID_Module_Class = mdcls.ID_Module_Class 
                ORDER BY
                    sdu.Shift_Schedules';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':teacher_id' => $this->teacher_id
                ]);
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($data as &$e) {
                    $e['Shift_Schedules'] = intval($e['Shift_Schedules']);
                }

                return $data;

            } catch (PDOException $error) {
                printError($error);

                return 'Failed';
            }
        }
    }