<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/shared/functions.php';

    class FixSchedule
    {
        private const fix_table = 'Fix';
        private const schedules_table = 'Schedules';
        private const module_table = 'Module';
        private const module_class_table = 'Module_Class';
        private const teacher_table = 'Teacher';

        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function getFixSchedules($old_id_fix)
        {
            $sql_query = "
                SELECT
                    fix.ID_Fix, md.Module_Name, sch.ID_Module_Class, sch.Day_Schedules,
                    fix.Day_Fix, fix.Shift_Fix, fix.ID_Room, t.ID
                FROM
                    " . self::fix_table . " fix,
                    " . self::schedules_table . " sch,
                    " . self::module_table . " md,
                    " . self::module_class_table . " mdc,
                    " . self::teacher_table . " t
                WHERE
                    fix.ID_Fix > :old_id_fix AND
                    sch.ID_Schedules = fix.ID_Schedules AND
                    mdc.ID_Module_Class = sch.ID_Module_Class AND
                    md.ID_Module = mdc.ID_Module AND
                    t.ID_Teacher = mdc.ID_Teacher
                ";

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':old_id_fix' => $old_id_fix]);
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $data;

            } catch (PDOException $error) {
                printError($error);

                return 'Failed';
            }
        }
    }
