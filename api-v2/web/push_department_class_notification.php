<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/notification.php';
    include_once dirname(__DIR__, 2) . '/class/firebase_notification.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/helper.php';

    $response = [];

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        !empty($data)) {

        $db      = new Database();
        $connect = $db->connect();

        try {
            $helper = new Helper($connect);
            $helper->getListFromDepartmentClass($data['class_list']);

            $id_account_list = $helper->getAccountListFromStudentList();
            $notification    = new Notification($connect, $data['info'], $id_account_list);

            $token_list            = $helper->getTokenListFromStudentList();
            $firebase_notification = new FirebaseNotification($data['info'], $token_list);

            $notification->create();
            $firebase_notification->send();

            $response['status_code'] = 200;
            $response['content']     = 'OK';

        } catch (PDOException $error) {
            printError($error);
            $response['status_code'] = 500;
            $response['content']     = 'Error';
        }
    }
    else {
        $response['status_code'] = 406;
        $response['content']     = 'Invalid Request';
    }

    response($response, true);

