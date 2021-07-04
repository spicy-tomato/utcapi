<?php

    include_once dirname(__DIR__) . '/utils/env_io.php';

    class FacultyClass
    {
        private const class_table = 'Class';

        private PDO $connect;
        private array $class_info_list = [];

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function insert ()
        {
            $this->class_info_list = array_unique($this->class_info_list, SORT_REGULAR);

            $sql_data    = [];
            $part_of_sql = implode(',',
                array_fill(0, count($this->class_info_list), '(?,?,?,?)'));

            foreach ($this->class_info_list as $class_info) {
                $sql_data[] = $class_info[':id_class'];
                $sql_data[] = $class_info[':academic_year'];
                $sql_data[] = $class_info[':class_name'];
                $sql_data[] = $class_info[':id_faculty'];
            }

            $this->_insert($part_of_sql, $sql_data);
        }

        private function _insert ($part_of_sql, $sql_data)
        {
            $sql_query =
                'INSERT INTO
                    ' . self::class_table . '
                (
                    ID_Class, Academic_Year, Class_Name, ID_Faculty
                ) 
                VALUES
                    ' . $part_of_sql . '
                ON DUPLICATE KEY UPDATE ID_Class = ID_Class';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute($sql_data);

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getAcademicYear () : array
        {
            $sql_query =
                'SELECT DISTINCT
                    Academic_Year
                FROM 
                    ' . self::class_table . ' 
                ORDER BY 
                    Academic_Year DESC 
                LIMIT 9';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute();
                $record = $stmt->fetchAll(PDO::FETCH_COLUMN);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getAllFacultyClass ($academic_year) : array
        {
            $sql_query_1 =
                'CREATE TEMPORARY TABLE temp (
                  Academic_Year varchar(3) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

            $sql_of_list =
                implode(',', array_fill(0, count($academic_year), '(?)'));

            $sql_query_2 =
                'INSERT INTO temp
                    (Academic_Year)
                VALUES
                    ' . $sql_of_list;

            $sql_query_3 = '
                SELECT 
                    t.Academic_Year, ID_Faculty, ID_Class
                FROM 
                     temp t 
                         LEFT OUTER JOIN
                     ' . self::class_table . ' c
                        ON t.Academic_Year = c.Academic_Year
                ORDER BY 
                    Academic_Year ASC,
                    ID_Faculty ASC,
                    ID_Class ASC';

            try {
                $stmt = $this->connect->prepare($sql_query_1);
                $stmt->execute();

                $stmt = $this->connect->prepare($sql_query_2);
                $stmt->execute($academic_year);

                $stmt = $this->connect->prepare($sql_query_3);
                $stmt->execute();
                $record = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function extractData (&$student_list) : array
        {
            $data                  = [];
            $sql_student_data      = [];
            $sql_account_data      = [];
            $sql_data_version_data = [];

            $part_of_sql_student        = '';
            $part_of_sql_account        = '';
            $part_of_sql_data_version_1 = '';
            $part_of_sql_data_version_2 = '';

            foreach ($student_list as &$student) {
                $class_info              = $this->_getDataOfClass($student['ID_Class']);
                $this->class_info_list[] = $class_info;
                $student['ID_Class']     = $class_info[':id_class'];

                $sql_student_data[]  = $student['ID_Student'];
                $sql_student_data[]  = $student['Student_Name'];
                $sql_student_data[]  = $student['DoB'];
                $sql_student_data[]  = $student['ID_Class'];
                $part_of_sql_student .= '(?,?,?,?,null,null,null),';

                $sql_account_data[]  = $student['ID_Student'];
                $sql_account_data[]  = password_hash($student['DoB'], PASSWORD_DEFAULT);
                $part_of_sql_account .= '(?,null,?,null,0),';

                $sql_data_version_data[]    = $student['ID_Student'];
                $part_of_sql_data_version_1 .= '(?,0,0,0,0),';
                $part_of_sql_data_version_2 .= '?,';

            }

            $part_of_sql_student        = rtrim($part_of_sql_student, ',');
            $part_of_sql_account        = rtrim($part_of_sql_account, ',');
            $part_of_sql_data_version_1 = rtrim($part_of_sql_data_version_1, ',');
            $part_of_sql_data_version_2 = rtrim($part_of_sql_data_version_2, ',');

            $data['student']['sql']       = $part_of_sql_student;
            $data['account']['sql']       = $part_of_sql_account;
            $data['data_version']['sql1'] = $part_of_sql_data_version_1;
            $data['data_version']['sql2'] = $part_of_sql_data_version_2;

            $data['student']['arr']      = $sql_student_data;
            $data['account']['arr']      = $sql_account_data;
            $data['data_version']['arr'] = $sql_data_version_data;

            return $data;
        }

        private function _getDataOfClass ($id_class)
        {
            $id_class      = preg_replace('/\s+/', '', $id_class);
            $arr           = explode('.', $id_class);
            $academic_year = $arr[0];

            unset($arr[0]);
            $class = '';
            foreach ($arr as $a) {
                $class .= $a . '.';
            }
            $class = rtrim($class, '.');

            $num = substr($class, strlen($class) - 1, 1);
            if (is_numeric($num)) {
                if (!isset(EnvIO::$faculty_class_info[substr($class, 0, strlen($class) - 1)])) {
                    $data_info[':id_faculty'] = 'KHOAKHAC';
                    $data_info[':class_name'] = 'Lớp thuộc khoa khác';
                }
                else {
                    $data_info                = EnvIO::$faculty_class_info[substr($class, 0, strlen($class) - 1)];
                    $name_academic_year       = substr_replace($academic_year, 'hóa ', 1, 0);
                    $data_info[':class_name'] = $data_info[':class_name'] . ' ' . $num . ' - ' . $name_academic_year;
                }
            }
            else {
                if (!isset(EnvIO::$faculty_class_info[$class])) {
                    $data_info[':id_faculty'] = 'KHOAKHAC';
                    $data_info[':class_name'] = 'Lớp thuộc khoa khác';
                }
                else {
                    $data_info                = EnvIO::$faculty_class_info[$class];
                    $name_academic_year       = substr_replace($academic_year, 'hóa ', 1, 0);
                    $data_info[':class_name'] = $data_info[':class_name'] . ' - ' . $name_academic_year;
                }
            }
            $data_info[':id_class']      = $id_class;
            $data_info[':academic_year'] = $academic_year;

            return $data_info;
        }
    }
