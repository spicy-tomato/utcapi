<?php

    class ExamSchedule
    {
        private const exam_schedule_table = 'Exam_Schedule';

        private PDO $connect;
        private string $id_student;

        public function __construct (PDO $connect, $id_student)
        {
            $this->connect    = $connect;
            $this->id_student = $id_student;
        }

        public function pushData ($data)
        {
            $recent_latest_school_year = $this->_getRecentLatestSchoolYear();

            foreach ($data as $school_year => $module) {
                if ($this->_formatSchoolYear($school_year) < $recent_latest_school_year) {
                    $this->_deleteBySchoolYear($recent_latest_school_year);

                    return;
                }
                if ($this->_formatSchoolYear($school_year) == $recent_latest_school_year &&
                    empty($module)) {

                    $this->_deleteBySchoolYear($this->_formatSchoolYear($school_year));
                    return;
                }

                foreach ($module as $value) {
                    try {
                        $this->_insert($school_year, $value);

                    } catch (PDOException $error) {
                        if ($error->getCode() == 23000) {
                            $this->_updateData($school_year, $value);
                        }
                        else {
                            throw $error;
                        }
                    }
                }
            }
        }

        public function pushAllData ($data)
        {
            $sum = count($data);

            foreach ($data as $semester => $module) {
                foreach ($module as $value) {
                    try {
                        $this->_insert($semester, $value);

                    } catch (PDOException $error) {
                        if ($error->getCode() == 23000) {
                            if (count($data) == $sum) {
                                $this->_updateData($semester, $value);
                            }
                        }
                        else {
                            throw $error;
                        }
                    }
                }
                unset($data[$semester]);
            }
        }

        private function _insert ($semester, $value)
        {
            $sql_query = '
                INSERT INTO
                    ' . self::exam_schedule_table . ' 
                (
                School_Year, Examination, ID_Student, ID_Module, Module_Name, Credit,
                Date_Start, Time_Start, Method, Identification_Number, Room
                )
                VALUES
                (
                :school_year, :examination, :id_student, :id_module, :module_name, :credit,
                :date_start, :time_Start, :method, :identification_number, :room
                )';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':school_year' => $this->_formatSchoolYear($semester),
                    ':examination' => $value[0],
                    ':id_student' => $value[1],
                    ':id_module' => $value[2],
                    ':module_name' => $value[3],
                    ':credit' => $value[4],
                    ':date_start' => $value[5],
                    ':time_Start' => $value[6],
                    ':method' => $value[7],
                    ':identification_number' => $value[8],
                    ':room' => $value[9]
                ]);

            } catch (PDOException $error) {
                throw $error;
            }
        }

        private function _updateData ($semester, $value)
        {
            $sql_query =
                'UPDATE
                    ' . self::exam_schedule_table . '
                SET  
                    Date_Start = :date_start, Time_Start = :time_Start, Method = :method,
                    Identification_Number = :identification_number, Room = :room
                WHERE 
                    School_Year = :school_year AND
                    ID_Student = :id_student AND
                    ID_Module = :id_module';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':school_year' => $this->_formatSchoolYear($semester),
                    ':id_student' => $value[1],
                    ':id_module' => $value[2],
                    ':date_start' => $value[5],
                    ':time_Start' => $value[6],
                    ':method' => $value[7],
                    ':identification_number' => $value[8],
                    ':room' => $value[9]
                ]);

            } catch (PDOException $error) {
                throw $error;
            }
        }

        private function _deleteBySchoolYear ($school_year)
        {
            $sql_query = '
                DELETE
                FROM
                    ' . self::exam_schedule_table . '
                WHERE
                    School_Year = :school_year AND
                    ID_Student = :id_student
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':school_year' => $school_year,
                    ':id_student' => $this->id_student
                ]);

            } catch (PDOException $error) {
                throw $error;
            }
        }

        private function _getRecentLatestSchoolYear ()
        {
            $sql_query = '
                SELECT 
                   School_Year 
                FROM ' . self::exam_schedule_table . ' 
                WHERE
                    ID_Student = :id_student
                ORDER BY 
                    School_Year DESC 
                LIMIT 0,1';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_student' => $this->id_student]);
                $record = $stmt->fetch(PDO::FETCH_COLUMN);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getExamSchedule () : array
        {
            $sql_query =
                'SELECT
                    School_Year, Module_Name, Credit, Date_Start,
                    Time_Start, Method, Identification_Number, Room
                FROM
                    ' . self::exam_schedule_table . '
                WHERE
                    ID_Student = :id_student
                ORDER BY
                    Date_Start
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_student' => $this->id_student]);
                $record = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $record = $this->_formatExamScheduleResponse($record);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        private function _formatSchoolYear ($school_year) : string
        {
            $semester_split = explode('_', $school_year);
            $school_year    = $semester_split[1] . '_' . $semester_split[2] . '_' . $semester_split[0];

            return $school_year;
        }

        private function _formatExamScheduleResponse ($data)
        {
            foreach ($data as &$value) {
                $value['Credit']                = intval($value['Credit']);
                $value['Identification_Number'] = intval($value['Identification_Number']);
                $date_split                     = explode('-', $value['Date_Start']);
                $value['Date_Start']            = $date_split[2] . '-' . $date_split[1] . '-' . $date_split[0];
            }

            return $data;
        }
    }