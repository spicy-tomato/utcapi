<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . '/utcapi/shared/functions.php';

    class StudentSchedule
    {
        private const module_class_table = 'Module_Class';
        private const schedule_table = 'Schedules';
        private const student_table = 'Student';
        private const participate_table = 'Participate';
        private const module_table = 'Module';

        private string $student_id;
        private PDO $connect;

        public function __construct (PDO $connect, string $student_id)
        {
            $this->connect    = $connect;
            $this->student_id = $student_id;
        }

        public function getAll ()
        {
            $sql_query =
                "SELECT
                    mc.Module_Name,
                    sdu.ID_Module_Class, sdu.ID_Room, sdu.Shift_Schedules, sdu.Day_Schedules
                FROM
                    " . self::module_table . " mc,
                    " . self::schedule_table . " sdu,
                    " . self::student_table . " stu,
                    " . self::participate_table . " par,
                    " . self::module_class_table . " mdcls
                WHERE
                    stu.ID_Student = :id_student AND
                    par.ID_Student = :id_student AND
                    sdu.ID_Module_Class = par.ID_Module_Class AND
                    mdcls.ID_Module_Class = sdu.ID_Module_Class AND
                    mc.ID_Module = mdcls.ID_Module
                ORDER BY
                    sdu.Shift_Schedules";

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_student' => $this->student_id]);
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
