<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/module_score.php';

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id'])) {

        try {
            $db      = new Database();
            $connect = $db->connect();

            $module_score = new ModuleScore($connect);
            $response     = $module_score->getScore($_GET['id']);

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
