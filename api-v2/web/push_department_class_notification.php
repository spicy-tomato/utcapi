<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/notification.php';
    include_once dirname(__DIR__, 2) . '/class/notification_by_id_account.php';
    include_once dirname(__DIR__, 2) . '/class/device.php';
    include_once dirname(__DIR__, 2) . '/class/firebase_notification.php';
    include_once dirname(__DIR__, 2) . '/class/helper.php';
    set_error_handler('exceptions_error_handler');

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($data['class_list']) &&
        isset($data['info'])) {

        try {
            $db      = new Database(true);
            $connect = $db->connect();

            $helper = new Helper($connect);
            $helper->getListFromDepartmentClass($data['class_list']);
            $id_student_list = $helper->getIdStudentList();
            $id_account_list = $helper->getAccountListFromStudentList();

            $device     = new Device($connect);
            $token_list = $device->getTokenByIdStudent($id_student_list);

            $notification               = new Notification($connect, $data['info']);
            $notification_by_id_account = new NotificationByIDAccount($connect);
            $firebase_notification      = new FirebaseNotification($data['info'], $token_list);

            $id_notification = $notification->create();
            $notification_by_id_account->pushData($id_account_list, $id_notification);
            $firebase_notification->send();

            $response['status_code'] = 200;
            $response['content']     = 'OK';

        } catch (PDOException $error) {
            printError($error);
            $response['status_code'] = 500;
        }
    }
    else {
        $response['status_code'] = 400;
    }

    response($response, true);

