<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/module_class.php';
    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        try {
            $db      = new Database();
            $connect = $db->connect();

            $module_class = new ModuleClass($connect);
            $response     = $module_class->getAll();

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
