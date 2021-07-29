<?php

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/module_score.php';
    include_once dirname(__DIR__, 2) . '/class/data_version_student.php';
    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id_student'])) {

        try {
            $db      = new Database(false);
            $connect = $db->connect();

            $module_score = new ModuleScore($connect, $_GET['id_student'], true);
            $data         = $module_score->getScore();

            if (empty($data)) {
                $response['status_code'] = 204;
            }
            else {
                $response['status_code'] = 200;
                $response['content']     = $data;
            }

        } catch (Error | Exception $error) {
            printError($error);
            $response['status_code'] = 500;
        }
    }
    else {
        $response['status_code'] = 400;
    }

    response($response, true);
