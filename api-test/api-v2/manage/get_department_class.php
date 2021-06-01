<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . '/config/db.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/class/department_class.php';

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $db      = new Database();
        $connect = $db->connect();

        $department_class = new DepartmentClass($connect);
        $response         = $department_class->getAll();
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);
