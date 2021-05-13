<?php


    include_once dirname(__DIR__) . '/shared/functions.php';

    class ModuleScore
    {
        private const module_score_table = 'Module_Score';

        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function pushData ($data) : string
        {
            $response = 'OK';

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

            foreach ($data as $semester => $module) {
                foreach ($module as $value) {
                    $stmt = $this->connect->prepare($sql_query);

                    try {
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
                        if ($error->getCode() == 23000) {
                            if (count($data) == 1) {
                                $this->_updateData($semester, $value);
                            }
                            $response = 'OK';
                        }
                        else {
                            printError($error);
                            $response = 'Failed';
                        }
                    }
                }
                unset($data[$semester]);
            }

            return $response;
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

            $stmt = $this->connect->prepare($sql_query);
            try {
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
                printError($error);
            }
        }

        public function getScore ($id_student)
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

                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $data = $this->_formatScoreResponse($data);

                return $data;

            } catch (PDOException $error) {
                printError($error);

                return 'Failed';
            }
        }

        private function _formatScoreResponse ($data)
        {
            foreach ($data as &$e) {
                $e['Credit']            = intval($e['Credit']);
                $e['Process_Score']     = floatval($e['Process_Score']);
                $e['Test_Score']        = floatval($e['Test_Score']);
                $e['Theoretical_Score'] = floatval($e['Theoretical_Score']);
            }

            return $data;
        }

        public function getSemester ($id_student)
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
                $stmt->execute([':id_student' => $id_student]);

                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $data;

            } catch (PDOException $error) {
                printError($error);

                return 'Failed';
            }
        }
    }
