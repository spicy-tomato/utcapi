<?php

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

        public function getFixedSchedules ($last_time_accepted) : array
        {
            $sql_query =
                'SELECT
                    fix.Time_Accept_Request, md.Module_Name, sch.ID_Module_Class, 
                    fix.Day_Fix, sch.Day_Schedules, sch.Shift_Schedules, sch.ID_Room, t.ID_Account
                FROM
                    ' . self::fix_table . ' fix,
                    ' . self::schedules_table . ' sch,
                    ' . self::module_table . ' md,
                    ' . self::module_class_table . ' mdc,
                    ' . self::teacher_table . ' t
                WHERE
                    fix.Time_Accept_Request > :last_time_accepted AND
                    sch.ID_Schedules = fix.ID_Schedules AND
                    mdc.ID_Module_Class = sch.ID_Module_Class AND
                    md.ID_Module = mdc.ID_Module AND
                    t.ID_Teacher = mdc.ID_Teacher AND
                    fix.Time_Accept_Request IS NOT NULL AND 
                    fix.Status_Fix = \'Chấp nhận\'
                ORDER BY 
                    fix.Time_Accept_Request';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':last_time_accepted' => $last_time_accepted]);
                $record = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function deleteTemporaryTable ()
        {
            $sql_query =
                'DROP TEMPORARY TABLE temp;
                 DROP TEMPORARY TABLE temp1
                 DROP TEMPORARY TABLE temp3';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute();

            } catch (PDOException $error) {
                throw $error;
            }
        }
    }
