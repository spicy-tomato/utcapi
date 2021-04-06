<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/print_error.php";

    class TeacherSchedule
    {
        private const module_table = "Module";
        private const module_class_table = "Module_Class";
        private const schedule_table = "Schedules";
        private const teacher_table = "Teacher";

        private string $teacher_id;
        private PDO $conn;

        public function __construct (PDO $conn, string $teacher_id)
        {
            $this->conn       = $conn;
            $this->teacher_id = $teacher_id;
        }

        public function getAll ()
        {
            $sqlQuery =
                "SELECT
                    mc.Module_Name,
                    sdu.ID_Module_Class, sdu.ID_Room, sdu.Shift_Schedules, sdu.Day_Schedules
                FROM
                    " . self::teacher_table . " tea, 
                    " . self::module_table . " mc,
                    " . self::schedule_table . " sdu,
                    " . self::module_class_table . " mdcls
                WHERE
                    tea.ID_Teacher = :teacher_id AND 
                    mdcls.ID_Teacher = :teacher_id AND 
                    mc.ID_Module = mdcls.ID_Module AND
                    sdu.ID_Module_Class = mdcls.ID_Module_Class 
                ORDER BY
                    sdu.Shift_Schedules";

            try {
                $stmt = $this->conn->prepare($sqlQuery);
                $stmt->execute([':teacher_id' => $this->teacher_id]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                printError($e);

                return "Failed";
            }
        }

        public function getByTIme (string $from, string $to)
        {
            $sqlQuery =
                "SELECT
                    mc.Module_Name,
                    sdu.ID_Module_Class, sdu.ID_Room, sdu.Shift_Schedules, sdu.Day_Schedules
                FROM
                    " . self::module_table . " mc,
                    " . self::schedule_table . " sdu,
                    " . self::module_class_table . " mdcls
                WHERE
                    mdcls.ID_Teacher = :teacher_id AND 
                    mc.ID_Module = mdcls.ID_Module AND
                    sdu.ID_Module_Class = mdcls.ID_Module_Class AND
                    sdu.Day_Schedules >= :from AND
                    sdu.Day_Schedules <= :to
                ORDER BY
                    sdu.Shift_Schedules";

            try {
                $stmt = $this->conn->prepare($sqlQuery);
                $stmt->execute([
                    ':student_id' => $this->teacher_id,
                    ':from' => $from,
                    ':to' => $to]);

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                printError($e);

                return "Failed";
            }
        }

    }