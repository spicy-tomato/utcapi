<?php

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
            $sql_query =
                'SELECT
                    password, id, permission
                FROM
                     ' . self::account_table . '
                WHERE
                    username = :username';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':username' => $request_data['username']
                ]);
                $record = $stmt->fetch(PDO::FETCH_ASSOC);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getDataAccountOwner ($id_account, $table_name) : array
        {
            $sql_query =
                'SELECT
                    * 
                FROM 
                     ' . $table_name . '
                WHERE 
                    ID_Account = :id_account';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':id_account' => $id_account]);

                $record = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$record) {
                    $data['status_code'] = 401;
                    $data['content']     = 'Invalid Account';
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

        public function getAccountPermission ($username) : string
        {
            $sql_query =
                'SELECT
                    permission
                FROM
                    ' . self::account_table . '
                WHERE
                    username = :username';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([':username' => $username]);
                $record = $stmt->fetch(PDO::FETCH_ASSOC);

                return $record['permission'] ?? 'Not Found';

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getIDAccount ($id) : string
        {
            $sql_query =
                'SELECT
                        a.id
                    FROM
                         ' . self::student_table . ' s,
                         ' . self::teacher_table . ' t,
                         ' . self::account_table . ' a  
                    WHERE
                        ((s.ID_Student = :id AND s.ID_Account = a.id) OR
                        (t.ID_Teacher = :id AND t.ID_Account = a.id)) 
                    LIMIT 1';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute(['id' => $id]);
                $record = $stmt->fetch(PDO::FETCH_ASSOC);

                return $record['id'] ?? 'Not Found';

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function updateQLDTPasswordOfStudentAccount ($id, $qldt_password) : array
        {
            $sql_query =
                'UPDATE
                         ' . self::account_table . '
                    SET 
                        qldt_password = :qldt_password
                    WHERE
                        id = :id';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute([
                    ':id' => $id,
                    ':qldt_password' => $qldt_password
                ]);

                $data['status_code'] = 200;
                $data['content']     = 'OK';

                return $data;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getQLDTPasswordOfStudentAccount ($id) : string
        {
            $sql_query =
                'SELECT
                        qldt_password
                    FROM
                         ' . self::account_table . ' a  
                    WHERE
                        id = :id';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute(['id' => $id]);
                $record = $stmt->fetch(PDO::FETCH_COLUMN);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function autoCreateStudentAccount ($sql_data, $part_of_sql)
        {
            $this->_autoCreateStudentAccount($part_of_sql, $sql_data);
        }

        private function _autoCreateStudentAccount ($part_of_sql, $sql_data)
        {
            $sql_query =
                'INSERT INTO ' . self::account_table . ' 
                (
                     username, email, password, qldt_password, permission
                ) 
                VALUES 
                    ' . $part_of_sql . '
                ON DUPLICATE KEY UPDATE username = username';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute($sql_data);

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function bindIDAccountToStudent ()
        {
            $sql_query =
                'UPDATE 
                    ' . self::student_table . ',
                    ' . self::account_table . '
                SET 
                    ID_Account = id
                WHERE 
                    ID_Student = username AND 
                    ID_Account IS NULL';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute();

            } catch (PDOException $error) {
                throw $error;

            }
        }
    }