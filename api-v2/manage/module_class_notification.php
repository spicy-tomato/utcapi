<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/notification.php";
    include_once 'helper.php';

    $response = 'No request';
    $data     = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        $data != null) {

        $db   = new Database();
        $conn = $db->connect();

        $helper       = new Helper($conn);
        $student_list = $helper->moduleClassListToStudentList($data['class_list']);

        $notification = new Notification($conn, $data['info'], $student_list);
        if (isset($_POST['time'])) {
            $notification->setTime($_POST['time']);
        }

        try {
            $notification->create();
            $response = 'OK';

        } catch (PDOException $error) {
            $response = 'Failed';
        }
    }

    echo json_encode($response);
