<?php
    include_once dirname(__DIR__) . '/shared/functions.php';

    class Account
    {
        private const account_table = 'Account';
        private const student_table = 'Student';
        private const teacher_table = 'Teacher';
        private const other_department_table = 'Other_Department';
        private const department_table = 'Department';
        private const faculty_table = 'Faculty';

        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function login_app ($request_data) : array
        {
            $sql_query = '
                SELECT
                    *
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

                $response = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$response) {
                    $data['message'] = 'failed';
                }
                else {
                    switch ($response['permission']) {
                        case '0':
                            $data = $this->_getDataAccountOwner($response['id'], self::student_table);
                            break;

                        case '1':
                            $data = $this->_getDataAccountOwner($response['id'], self::teacher_table);
                            break;

                        default:
                            $data['message'] = 'failed';
                    }
                }

                return $data;

            } catch (PDOException $error) {
                printError($error);
                $data['message'] = 'failed';

                return $data;
            }
        }

        public function login_web ($request_data) : array
        {
            $sql_query = '
                SELECT
                    *
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

                $response = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$response) {
                    $data['message'] = 'failed';
                }
                else {
                    switch ($response['permission']) {
                        case '1':
                            $data = $this->_getDataAccountOwner($response['id'], self::teacher_table);
                            if ($data['message'] != 'failed') {
                                $data['info']['account_owner'] = $data['info']['Name_Teacher'];
                            }
                            break;

                        case '2':
                            $data = $this->_getDataAccountOwner($response['id'], self::department_table);
                            if ($data['message'] != 'failed') {
                                $data['info']['account_owner'] = $data['info']['Department_Name'];
                            }
                            break;

                        case '3':
                            $data = $this->_getDataAccountOwner($response['id'], self::faculty_table);
                            if ($data['message'] != 'failed') {
                                $data['info']['account_owner'] = $data['info']['Faculty_Name'];
                            }
                            break;

                        case '4':
                            $data = $this->_getDataAccountOwner($response['id'], self::other_department_table);
                            if ($data['message'] != 'failed') {
                                $data['info']['account_owner'] = $data['info']['Other_Department_Name'];
                            }
                            break;

                        default:
                            $data['message'] = 'failed';
                    }
                }

                return $data;

            } catch (PDOException $error) {
                printError($error);
                $data['message'] = 'failed';

                return $data;
            }
        }

        private function _getDataAccountOwner ($id_account, $table_name) : array
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

                $response = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$response) {
                    $data['message'] = 'failed';
                }
                else {
                    $data['message'] = 'success';
                    $data['info']    = $response;
                }

                return $data;

            } catch (PDOException $error) {
                printError($error);
                $data['message'] = 'failed';

                return $data;
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

        public function getDepartmentInfoByIDAccount ($id_account)
        {
            $sql_query = '
                    SELECT
                        Department_Name, ID
                    FROM
                         ' . self::department_table . '
                    WHERE
                        ID = :id_account
                    ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_account' => $id_account]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return $data;

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