<?php
    include_once dirname(__DIR__) . '/shared/functions.php';

    class Account
    {
        private const account_table = 'Account';
        private const student_table = 'Student';
        private const teacher_table = 'Teacher';

        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function login ($request_data)
        {
            $sql_query = '
                SELECT
                    id, permission
                FROM
                     ' . self::account_table . '
                WHERE
                    username = :username AND
                    password = :password
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':username' => $request_data['username'],
                    ':password' => md5($request_data['password'])
                ]);
                $record = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$record)
                {
                    $record = 'Failed';
                }

                return $record;

            } catch (PDOException $error) {
                printError($error);
                throw $error;
            }
        }

        public function getDataAccountOwner ($id_account, $table_name) : array
        {
            $sql_query = '
                SELECT
                    * 
                FROM 
                     ' . $table_name . '
                WHERE 
                    ID = :id
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id' => $id_account]);

                $record = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$record) {
                    $data['status_code'] = 404;
                    $data['content']['message'] = 'failed';
                }
                else {
                    $data['status_code'] = 200;
                    $data['content']['message'] = 'success';
                    $data['content']['info']    = $record;
                }

                return $data;

            } catch (PDOException $error) {
                printError($error);
                throw $error;
            }
        }

        public function getAccountPermission ($username) : string
        {
            $sql_query =
                'SELECT
                    permission
                FROM
                    ' . self::account_table . '
                WHERE
                    username = :username
                ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':username' => $username]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return isset($data['permission']) ? $data['permission'] : 'Not Found';

            } catch (PDOException $error) {
                printError($error);

                return 'Failed';
            }
        }

        public function getIDAccount ($id) : string
        {
            $sql_query = '
                    SELECT
                        a.id
                    FROM
                         ' . self::student_table . ' s,
                         ' . self::teacher_table . ' t,
                         ' . self::account_table . ' a  
                    WHERE
                        ((s.ID_Student = :id AND s.ID = a.id) OR
                        (t.ID_Teacher = :id AND t.ID = a.id)) 
                    LIMIT 1
                    ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute(['id' => $id]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return isset($data['id']) ? $data['id'] : 'Not Found';

            } catch (PDOException $error) {
                printError($error);

                return 'Failed';
            }
        }

        public function updateQLDTPasswordOfStudentAccount ($id, $qldt_password) : string
        {
            $sql_query = '
                    UPDATE
                         ' . self::account_table . '
                    SET 
                        qldt_password = :qldt_password
                    WHERE
                        id = :id
                    ';

            try {
                $stmt   = $this->connect->prepare($sql_query);
                $status = $stmt->execute([
                    'id' => $id,
                    ':qldt_password' => $qldt_password
                ]);

                return $status ? 'OK' : 'Not Found';

            } catch (PDOException $error) {
                printError($error);

                return 'Failed';
            }
        }

        public function getQLDTPasswordOfStudentAccount ($id) : string
        {
            $sql_query = '
                    SELECT
                        qldt_password
                    FROM
                         ' . self::account_table . ' a  
                    WHERE
                        id = :id
                    ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute(['id' => $id]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return isset($data['qldt_password']) ? $data['qldt_password'] : 'Not Found';

            } catch (PDOException $error) {
                printError($error);

                return 'Failed';
            }
        }
    }