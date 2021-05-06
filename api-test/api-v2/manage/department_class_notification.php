<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/notification.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/firebase_notification.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/shared/functions.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/helper.php";

    $response = 'No request';

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        !empty($data)) {

        $db = new Database();
        $connect = $db->connect();

        $helper = new Helper($connect);
        $helper->getListFromDepartmentClass($data['class_list']);

        $id_account_list = $helper->getAccountListFromStudentList();
        $notification = new Notification($connect, $data['info'], $id_account_list);

        $token_list = $helper->getTokenListFromStudentList();
        var_dump($token_list);
        $firebase_notification = new FirebaseNotification($data['info'], $token_list);

        try {
            $notification->create();
            $firebase_notification->send();
            $response = 'OK';

        } catch (PDOException $error) {
            printError($error);

            $response = 'Failed';
        }
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);
