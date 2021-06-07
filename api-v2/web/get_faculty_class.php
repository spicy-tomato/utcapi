<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/faculty_class.php';
    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        try {
            $db      = new Database(true);
            $connect = $db->connect();

            $faculty_class = new FacultyClass($connect);
            $data1         = $faculty_class->getAcademicYear();
            $data2         = $faculty_class->getAllFacultyClass($data1);

            $response['status_code']              = 200;
            $response['content']['academic_year'] = $data1;
            $response['content']['all_class']     = $data2;

        } catch (Error | Exception $error) {
            printError($error);
            $response['status_code'] = 500;
        }
    }
    else {
        $response['status_code'] = 400;
    }

    response($response, true);
