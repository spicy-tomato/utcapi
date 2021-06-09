<?php

    class ModuleClass
    {
        private const module_table = 'Module_Class';
        private PDO $connect;

        public function __construct (PDO $connect)
        {
            $this->connect = $connect;
        }

        public function getAllModuleClass () : array
        {
            $latest_semester = $this->getLatestSemester();
            $first_semester  = '';
            $second_semester = '';

            switch (intval(substr($latest_semester, 0, 1))) {
                case 1:
                    $first_semester  = '2' . '-' . (intval(substr($latest_semester, 2, 2)) - 1);
                    $second_semester = $latest_semester;
                    break;

                case 2:
                    $first_semester  = '1' . '-' . substr($latest_semester, 2, 2);
                    $second_semester = $latest_semester;
            }

            $sql_query_1 = '
                CREATE TEMPORARY TABLE temp(
                     School_Year varchar(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
                ) ENGINE = InnoDB default CHARSET = utf8 COLLATE = utf8_unicode_ci
                ';

            $sql_query_2 =
                'INSERT INTO temp
                    (School_Year)
                VALUES
                    (?),(?)';

            $sql_query_3 = '
                SELECT
                    ID_Module_Class, Module_Class_Name
                FROM
                     temp t
                         LEFT OUTER JOIN
                     ' . self::module_table . ' mc
                        ON t.School_Year = mc.School_Year
                ORDER BY
                    ID_Module_Class
                ';

            try {
                $stmt = $this->connect->prepare($sql_query_1);
                $stmt->execute();

                $stmt = $this->connect->prepare($sql_query_2);
                $stmt->execute([$first_semester, $second_semester]);

                $stmt = $this->connect->prepare($sql_query_3);
                $stmt->execute();
                $record = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $record;

            } catch (PDOException $error) {
                throw $error;
            }
        }

        public function getLatestSemester ()
        {
            $sql_query = 'SELECT MAX(School_Year) latest_semester from ' . self::module_table;

            try {
                $stmt = $this->connect->prepare($sql_query);
                $stmt->execute();
                $record = $stmt->fetch(PDO::FETCH_ASSOC);;

                return $record['latest_semester'];

            } catch (PDOException $error) {
                throw $error;
            }
        }
    }
