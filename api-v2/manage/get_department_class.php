<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/department_class.php";

    $db               = new Database();
    $conn             = $db->connect();
    $res              = null;
    $department_class = new DepartmentClass($conn);

    switch ($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $res = $department_class->getAll();
            break;

        default:
            echo null;
            exit();
    }

    echo json_encode($res);
