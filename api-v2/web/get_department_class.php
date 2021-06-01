<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/department_class.php';
    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        try {
            $db      = new Database();
            $connect = $db->connect();

            $department_class = new DepartmentClass($connect);
            $response         = $department_class->getAll();

        } catch (Exception $error) {
            printError($error);
            $response['status_code'] = 500;
            $response['content']     = 'Error';
        }
    }
    else {
        $response['status_code'] = 406;
        $response['content']     = 'Invalid Request';
    }

    response($response, true);
