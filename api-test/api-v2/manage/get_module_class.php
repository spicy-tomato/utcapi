<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . "/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/class/module_class.php";

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
