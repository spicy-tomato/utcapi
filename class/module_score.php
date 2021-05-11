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

            foreach ($data as $key => $value) {
                foreach ($value as $item) {
                    $stmt = $this->connect->prepare($sql_query);

                    try {
                        $stmt->execute([':semester' => $key,
                            ':id_module' => $item[0],
                            ':module_name' => $item[1],
                            ':credit' => $item[2],
                            ':id_student' => $item[4],
                            ':evaluation' => $item[3],
                            ':process_score' => $item[5],
                            ':test_score' => $item[6],
                            ':theoretical_score' => $item[7]]);

                    } catch (PDOException $error) {
                        if ($error->getCode() == 23000 &&
                            count($data) == 1) {
                            $sql_query_2 =
                                'UPDATE
                                    ' . self::module_score_table . '
                                SET  
                                    Evaluation = :evaluation, Process_Score = :process_score, 
                                    Test_Score = :test_score, Theoretical_Score = :theoretical_score
                                WHERE 
                                    Semester = :semester AND
                                    ID_Module = :id_module AND
                                    ID_Student = :id_student';

                            $stmt = $this->connect->prepare($sql_query_2);
                            $stmt->execute([':semester' => $key,
                                ':id_module' => $item[0],
                                ':id_student' => $item[4],
                                ':evaluation' => $item[3],
                                ':process_score' => $item[5],
                                ':test_score' => $item[6],
                                ':theoretical_score' => $item[7]]);
                        }
                        else {
                            printError($error);

                            $response = 'Failed';
                        }
                    }
                }
                unset($data[$key]);
            }

            return $response;
        }

        public function getScore ($is_student)
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
                $stmt->execute([':id_student' => $is_student]);

                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $data = $this->_formatResponse($data);

                return $data;

            } catch (PDOException $error) {
                printError($error);

                return 'Failed';
            }
        }

        private function _formatResponse ($data)
        {
            foreach ($data as &$e) {
                $e['Credit']            = intval($e['Credit']);
                $e['Process_Score']     = floatval($e['Process_Score']);
                $e['Test_Score']        = floatval($e['Test_Score']);
                $e['Theoretical_Score'] = floatval($e['Theoretical_Score']);
            }

            return $data;
        }
    }
