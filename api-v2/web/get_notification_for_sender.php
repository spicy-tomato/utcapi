<?php

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/notification.php';
    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id_sender']) &&
        isset($_GET['num'])) {

        try {
            $db      = new Database(true);
            $connect = $db->connect();

            $notification = new Notification($connect);
            $data         = $notification->getNotificationForSender($_GET['id_sender'], $_GET['num']);

            if (empty($data)) {
                $response['status_code'] = 204;
            }
            else {
                $response['status_code'] = 200;
                $response['content']     = $data;
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