<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/module_class.php";

    $db           = new Database();
    $conn         = $db->connect();
    $res          = null;
    $module_class = new ModuleClass($conn);

    switch ($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $res = $module_class->getAll();
            break;

        default:
            echo null;
            exit();
    }

    echo json_encode($res);
