<?php

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/class/module_class.php';

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $db      = new Database();
        $connect = $db->connect();

        $module_class = new ModuleClass($connect);
        $response     = $module_class->getAll();
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);
