<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/module_class.php';

    $response = [];

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $db      = new Database();
        $connect = $db->connect();

        $module_class = new ModuleClass($connect);
        $response     = $module_class->getAll();
    }
    else {
        $response['content']     = 'Invalid Request';
        $response['status_code'] = 406;
    }

    response($response);
