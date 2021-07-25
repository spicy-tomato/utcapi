<?php

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/notification.php';
    include_once dirname(__DIR__, 2) . '/class/data_version_student.php';

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        !empty($data)) {

        try {
            $db      = new Database(true);
            $connect = $db->connect();

            $notification         = new Notification($connect);
            $data_version_student = new DataVersionStudent($connect);

            $notification->setDeleteNotification($data);
            foreach ($data as $id_notification) {
                $data_version_student->updateAllNotificationVersion($id_notification);
            }

            $response['status_code'] = 200;
            $response['content']     = 'OK';

        } catch (Error | Exception $error) {
            printError($error);
            $response['status_code'] = 500;
        }
    }
    else {
        $response['status_code'] = 400;
    }

    response($response, true);