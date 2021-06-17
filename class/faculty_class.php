<?php

    include_once dirname(__DIR__) . '/utils/env_io.php';

    class FacultyClass
    {
        private const class_table = 'Class';

        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function insert ($id_class)
        {
            $sql_query = '
                    INSERT INTO
                        ' . self::class_table . '
                        (ID_Class, Academic_Year, Class_Name, ID_Faculty) 
                    VALUES
                        (:id_class, :academic_year, :class_name, :id_faculty)
                    ON DUPLICATE KEY UPDATE ID_Class = ID_Class
                    ';

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute($this->_getDataOfClass($id_class));

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getAcademicYear () : array
        {
            $sql_query = '
                    SELECT DISTINCT
                        Academic_Year
                    FROM ' . self::class_table . ' 
                    ORDER BY 
                        Academic_Year DESC 
                    LIMIT 9
                    ';

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
            $sql_query_1 = '
                CREATE TEMPORARY TABLE temp (
                  Academic_Year varchar(3) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
                ';

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
                    ID_Class ASC
                ';

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
                $data_info                = EnvIO::$faculty_class_info[substr($class, 0, strlen($class) - 1)];
                $name_academic_year       = substr_replace($academic_year, 'hóa ', 1, 0);
                $data_info[':class_name'] = $data_info[':class_name'] . ' ' . $num . ' - ' . $name_academic_year;
            }
            else {
                $data_info                = EnvIO::$faculty_class_info[$class];
                $name_academic_year       = substr_replace($academic_year, 'hóa ', 1, 0);
                $data_info[':class_name'] = $data_info[':class_name'] . ' - ' . $name_academic_year;
            }
            $data_info[':id_class']      = $id_class;
            $data_info[':academic_year'] = $academic_year;

            return $data_info;
        }
    }
