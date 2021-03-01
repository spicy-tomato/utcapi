<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/notification_by_id_student.php";

    $db               = new Database();
    $conn             = $db->connect();
    $res              = null;
    $notification_by_id_student = new NotificationByIDStudent($conn);

    switch ($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $res = $notification_by_id_student->getAll($_GET['ID_Student']);
            break;

        default:
            echo null;
            exit();
    }

    echo json_encode($res);