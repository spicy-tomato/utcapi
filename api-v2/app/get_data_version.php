<?php

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/data_version.php';
    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id_student'])) {

        try {
            $db           = new Database(true);
            $connect      = $db->connect();
            $data_version = new DataVersion($connect, $_GET['id_student']);

            $response['status_code'] = 200;
            $response['content']     = $data_version->getAllDataVersion();

        } catch (Error | Exception $error) {
            printError($error);
            $response['status_code'] = 500;
        }
    }
    else {
        $response['status_code'] = 400;
    }

    response($response, true);

