<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/academic_year.php";

    $db               = new Database();
    $conn             = $db->connect();
    $res              = null;
    $academic_year = new AcademicYear($conn);

    switch ($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $res = $academic_year->getAcademicYear();
            break;

        default:
            echo null;
            exit();
    }

    echo json_encode($res);
