<?php
    include_once dirname(__DIR__) . '/shared/functions.php';

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
            $sum = count($data);

            foreach ($data as $semester => $module) {
                var_dump($semester);
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
            $sql_query =
                'INSERT INTO
                    ' . self::module_score_table . ' 
                (
                Semester, ID_Module, Module_Name, Credit, ID_Student,
                Evaluation, Process_Score, Test_Score, Theoretical_Score
                )
                VALUES
                (
                :semester, :id_module, :module_name, :credit, :id_student,
                :evaluation, :process_score, :test_score, :theoretical_score
                )';


            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':semester' => $semester,
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
                    Semester = :semester AND
                    ID_Module = :id_module AND
                    ID_Student = :id_student';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':semester' => $semester,
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

        public function getScore ($id_student) : array
        {
            $sql_query =
                'SELECT
                    Semester, Module_Name, Credit, Evaluation, 
                    Process_Score, Test_Score, Theoretical_Score
                FROM
                    ' . self::module_score_table . '
                WHERE
                    ID_Student = :id_student
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_student' => $id_student]);

                $record = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $record = $this->_formatScoreResponse($record);

                if (empty($record)) {
                    $data['status_code'] = 200;
                    $data['content']     = 'Not Found';
                }
                else {
                    $data['status_code'] = 200;
                    $data['content']     = $record;
                }

                return $data;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getAllRecentSemester () : array
        {
            $sql_query =
                'SELECT DISTINCT 
                    Semester
                FROM
                    ' . self::module_score_table . '
                WHERE
                    ID_Student = :id_student
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_student' => $this->id_student]);
                $record = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $record = $this->_formatSemester($record);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getRecentLatestSemester () : array
        {
            $sql_query = '
                SELECT 
                   Semester 
                FROM ' . self::module_score_table . ' 
                WHERE
                    ID_Student = :id_student
                ORDER BY 
                    Semester DESC 
                LIMIT 0,1';


            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_student' => $this->id_student]);
                $record = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $record = $this->_formatSemester($record);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        private function _formatSemester ($data) : array
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
                $value['Credit']            = intval($value['Credit']);
                $value['Process_Score']     = floatval($value['Process_Score']);
                $value['Test_Score']        = is_null($value['Test_Score']) ? $value['Test_Score'] : floatval($value['Test_Score']);
                $value['Theoretical_Score'] = is_null($value['Theoretical_Score']) ? $value['Theoretical_Score'] : floatval($value['Theoretical_Score']);
            }

            return $data;
        }

    }
