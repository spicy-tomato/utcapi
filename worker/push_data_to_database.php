<?php

    class WorkWithDatabase
    {
        private const student_sql = "
                    INSERT IGNORE INTO Student 
                    (
                        ID_Student, Student_Name, DoB_Student, ID_Class, 
                        ID_Card_Number, Phone_Number_Student, Address_Student, ID 
                    ) 
                        VALUES ";

        private const module_sql = "
                    INSERT INTO Module 
                    (
                        ID_Module, Module_Name, Credit, Semester, Theory, 
                        Practice, Exercise, Project, Optionz, ID_Department
                    ) 
                    VALUES ";

        private const module_class_sql = "
                    INSERT INTO Module_Class 
                    (
                        ID_Module_Class, Module_Class_Name, Number_Plan, 
                        Number_Reality, School_Year, ID_Module, ID_Teacher
                    ) 
                        VALUES ";

        private const participate_sql = "
                    INSERT INTO Participate 
                    (
                        ID_Module_Class, ID_Student, Process_Score, Test_Score, 
                        Theoretical_Score, Status_Studying
                    ) 
                        VALUES ";

        private const schedule_sql = "
                    INSERT INTO Schedules 
                    (
                        ID_Module_Class, ID_Room, Shift_Schedules, 
                        Day_Schedules, Number_Student
                    ) 
                        VALUES ";


        private Database $db;
        private array $data;

        public function __construct (Database $db)
        {
            $this->db = $db;
        }

        public function setData (array $data) : void
        {
            $this->data = $data;
        }

        private function autoCreateStudentAccount ($id_student, $dob) : string
        {
            $sql = "INSERT INTO Account 
                            (
                                 Account_Username, 
                                 Account_Email, 
                                 Password, 
                                 Permission
                            ) 
                            VALUES 
                                (?,?,?,?)";

            $connect = $this->db->connect();
            $stmt = $connect->prepare($sql);
            $stmt->execute(array($id_student, NULL, md5($dob), 0));

            return $connect->lastInsertId();
        }

        private function isAccountExist ($username) : bool
        {
            $sql = "SELECT 
                        ID
                    FROM 
                        Account
                    WHERE 
                        Username = ?";

            $connect = $this->db->connect();
            $stmt = $connect->prepare($sql);
            $stmt->execute(array($username));

            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            return $res ? true : false;
        }

        private function _createSQL ($arr, $table_name) : string
        {
            $sql = "";
            switch ($table_name) {
                case "Student";
                    $sql = self::student_sql . "(";

                    if (!$this->isAccountExist($arr["ID_Student"]))
                    {
                        $arr['ID'] = $this->autoCreateStudentAccount($arr['ID_Student'], $arr['DoB']);
                    }
                    break;

                case "Module";
                    $sql = self::module_sql . "(";
                    break;

                case "Module_Class";
                    $sql = self::module_class_sql . "(";
                    break;

                case "Participate";
                    $sql = self::participate_sql . "(";
                    break;

                case "Schedules":
                    $sql = self::schedule_sql . "(";
                    break;
            }

            foreach ($arr as $item) {
                if ($item != null) {
                    $sql .= "'" . $item . "',";
                }
                else {
                    $sql .= "NULL,";
                }

            }

            $sql = rtrim($sql, ",");
            $sql .= ")";

            return $sql;
        }

        public function pushData ($table_name)
        {
            $connect = $this->db->connect();

            foreach ($this->data as $arr) {
                $sql = $this->_createSQL($arr, $table_name);

                $stmt = $connect->prepare($sql);
                $stmt->execute();
            }
        }
    }