<?php

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/data_version.php';
    include_once dirname(__DIR__, 2) . '/class/notification.php';
    include_once dirname(__DIR__, 2) . '/class/notification_by_id_account.php';
    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id_student']) &&
        isset($_GET['id_account'])) {

        try {
            $db      = new Database(true);
            $connect = $db->connect();

            $data_version               = new DataVersion($connect, $_GET['id_student']);
            $notification_by_id_account = new NotificationByIDAccount($connect);

            $data  = [];
            $data2 = [];

            if (isset($_GET['id_notification'])) {
                $data = $notification_by_id_account->getAllNotification($_GET['id_account'], $_GET['id_notification']);

                $notification = new Notification($connect);
                $data2        = $notification->getDeletedNotification();
            }
            else {
                $data = $notification_by_id_account->getAllNotification($_GET['id_account']);
            }

            $notification_version = $data_version->getDataVersion('Notification');

            if (empty($data) && empty($data2)) {
                $response['status_code'] = 204;
            }
            else {
                $response['status_code']             = 200;
                $response['content']['data']         = $data;
                $response['content']['index_del']    = $data2;
                $response['content']['data_version'] = $notification_version;

            }

        } catch (Error | Exception $error) {
            printError($error);
            $response['status_code'] = 500;
        }
    }
    else {
        $response['status_code'] = 400;
    }

    response($response, true);
