<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/notification.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/firebase_notification.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/print_error.php";
    include_once 'helper.php';

    $response = 'No request';

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        $data != null) {

        $db   = new Database();
        $conn = $db->connect();

        $helper = new Helper($conn);
        $helper->getListFromModuleClassList($data['class_list']);

        $id_account_list = $helper->getAccountListFromStudentList();
        $notification    = new Notification($conn, $data['info'], $id_account_list);

        $token_list            = $helper->getTokenListFromStudentList();
        $firebase_notification = new FirebaseNotification($data['info'], $token_list);

        try {
            $notification->create();

            $firebase_notification->send();

            $response = 'OK';

        } catch (PDOException $e) {
            printError($e);

            $response = 'Failed';
        }
    }

    echo json_encode($response);
