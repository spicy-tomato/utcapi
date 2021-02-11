<?php

    class Schedule
    {
        private const module_class_table = "Module_Class";
        private const schedule_table = "Schedules";
        private const student_table = "Student";
        private const participate_table = "Participate";
        private const module_table = "Module";

        private string $student_id;
        private PDO $conn;

        public function __construct(PDO $conn, string $student_id)
        {
            $this->conn       = $conn;
            $this->student_id = $student_id;
        }

        public function getAll(): array
        {
            $sqlQuery =
                "SELECT
                    mc.Module_Name,
                    sdu.ID_Module_Class, sdu.ID_Room, sdu.Shift_Schedules, sdu.Day_Schedules
                FROM
                    " . self::module_table . " mc ,
                    " . self::schedule_table . " sdu,
                    " . self::student_table . " stu,
                    " . self::participate_table . " par,
                    " . self::module_class_table . " mdcls
                WHERE
                    stu.ID_Student = :student_id AND 
                    par.ID_Student = :student_id AND
                    sdu.ID_Module_Class = par.ID_Module_Class AND
                    sdu.ID_Module_Class = mdcls.ID_Module_Class AND
                    mc.ID_Module = mdcls.ID_Module
                ORDER BY
                    sdu.Shift_Schedules";

            try {
                $stmt = $this->conn->prepare($sqlQuery);
                $stmt->execute([':student_id' => $this->student_id]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $error) {
                exit($error->getMessage());
            }
        }

        public function get(string $from, string $to): array
        {
            $sqlQuery =
                "SELECT
                    sdu.*, stu.*, par.*
                FROM
                    " . self::schedule_table . " sdu,
                    " . self::student_table . " stu,
                    " . self::participate_table . " par
                WHERE
                    stu.ID_Student = :student_id AND
                    par.ID_Student = :student_id AND
                    sdu.ID_Module_Class = par.ID_Module_Class AND
                    sdu.Day_Schedules >= :from AND
                    sdu.Day_Schedules <= :to";

            try {
                $stmt = $this->conn->prepare($sqlQuery);
                $stmt->execute([
                    ':student_id' => $this->student_id,
                    ':from' => $from,
                    ':to' => $to]);

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $error) {
                exit($error->getMessage());
            }
        }
    }
