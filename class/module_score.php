<?php

    class ModuleScore
    {
        private const module_score_table = 'Module_Score';

        private PDO $connect;
        private string $id_student;

        public function __construct (PDO $connect, $id_student)
        {
            $this->connect    = $connect;
            $this->id_student = $id_student;
        }

        public function pushData ($data)
        {
            foreach ($data as $semester => $module) {
                foreach ($module as $value) {
                    try {
                        if ($value[0] == 'ANHA1.4' || $value[0] == 'ANHA2.4') {
                            continue;
                        }

                        $this->_insert($semester, $value);

                    } catch (PDOException $error) {
                        if ($error->getCode() == 23000) {
                            $this->_updateData($semester, $value);
                        }
                        else {
                            throw $error;
                        }
                    }
                }
                unset($data[$semester]);
            }
        }

        public function pushAllData ($data)
        {
            foreach ($data as $school_year => $module) {
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
                unset($data[$school_year]);
            }
        }

        private function _insert ($school_year, $value)
        {
            $sql_query =
                'INSERT INTO
                    ' . self::module_score_table . ' 
                (
                School_Year, ID_Student, ID_Module, Module_Name, Credit,
                Evaluation, Process_Score, Test_Score, Theoretical_Score
                )
                VALUES
                (
                :school_year, :id_student, :id_module, :module_name, :credit,
                :evaluation, :process_score, :test_score, :theoretical_score
                )';


            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':school_year' => $school_year,
                    ':id_module' => $value[0],
                    ':module_name' => $value[1],
                    ':credit' => $value[2],
                    ':id_student' => $value[4],
                    ':evaluation' => $value[3],
                    ':process_score' => $value[5],
                    ':test_score' => $value[6],
                    ':theoretical_score' => $value[7]
                ]);

            } catch (PDOException $error) {
                throw $error;
            }
        }

        private function _updateData ($semester, $value)
        {
            $sql_query =
                'UPDATE
                    ' . self::module_score_table . '
                SET  
                    Evaluation = :evaluation, Process_Score = :process_score, 
                    Test_Score = :test_score, Theoretical_Score = :theoretical_score
                WHERE 
                    School_Year = :school_year AND
                    ID_Module = :id_module AND
                    ID_Student = :id_student';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':school_year' => $semester,
                    ':id_module' => $value[0],
                    ':id_student' => $value[4],
                    ':evaluation' => $value[3],
                    ':process_score' => $value[5],
                    ':test_score' => $value[6],
                    ':theoretical_score' => $value[7]
                ]);

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getScore () : array
        {
            $sql_query =
                'SELECT
                    ID, School_Year, Module_Name, Credit, Evaluation, 
                    Process_Score, Test_Score, Theoretical_Score
                FROM
                    ' . self::module_score_table . '
                WHERE
                    ID_Student = :id_student
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_student' => $this->id_student]);
                $record = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $record = $this->_formatScoreResponse($record);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getAllRecentSemester () : array
        {
            $sql_query =
                'SELECT DISTINCT 
                    School_Year
                FROM
                    ' . self::module_score_table . '
                WHERE
                    ID_Student = :id_student
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_student' => $this->id_student]);
                $record = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $record = $this->_formatSchoolYear($record);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getRecentLatestSchoolYear () : array
        {
            $sql_query = '
                SELECT 
                   School_Year 
                FROM ' . self::module_score_table . ' 
                WHERE
                    ID_Student = :id_student
                ORDER BY 
                    School_Year DESC 
                LIMIT 0,1';


            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_student' => $this->id_student]);
                $record = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $record = $this->_formatSchoolYear($record);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        private function _formatSchoolYear ($data) : array
        {
            $formatted_data = [];
            foreach ($data as $item) {
                $semester_split   = explode('_', $item);
                $formatted_data[] = $semester_split[2] . '_' . $semester_split[0] . '_' . $semester_split[1];
            }

            return $formatted_data;
        }

        private function _formatScoreResponse ($data)
        {
            foreach ($data as &$value) {
                $value['ID']                = intval($value['ID']);
                $value['Credit']            = intval($value['Credit']);
                $value['Process_Score']     = floatval($value['Process_Score']);
                $value['Test_Score']        = is_null($value['Test_Score']) ? $value['Test_Score'] : floatval($value['Test_Score']);
                $value['Theoretical_Score'] = is_null($value['Theoretical_Score']) ? $value['Theoretical_Score'] : floatval($value['Theoretical_Score']);
            }

            return $data;
        }

    }
