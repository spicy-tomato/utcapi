<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/notification.php";
    include_once 'helper.php';

    $res = null;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($_POST['info'])) {

        $db   = new Database();
        $conn = $db->connect();

        $helper       = new Helper($conn);
        $student_list = $helper->moduleClassListToStudentList($_POST['class_list']);

        $notification = new Notification($conn, $_POST['info'], $student_list);
        if ($_POST['time'] != null) {
            $notification->setTime($_POST['time']);
        }

        $notification->create();
    }

    echo json_encode($res);
