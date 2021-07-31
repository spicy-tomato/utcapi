<?php

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/guest_info.php';
    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id'])) {

        try {
            $db      = new Database(false);
            $connect = $db->connect();

            $guest_info   = new GuestInfo($connect);
            $data_version = $guest_info->getNotificationVersion($_GET['id']);

            $data_version['Notification_Data_Version'] = intval($data_version['Notification_Data_Version']);
            $data_version['Module_Score_Version']      = intval($data_version['Module_Score_Version']);
            $data_version['Exam_Schedule_Version']     = intval($data_version['Exam_Schedule_Version']);

            $response['status_code'] = 200;
            $response['content']     = $data_version;

        } catch (Error | Exception $error) {
            printError($error);
            $response['status_code'] = 500;
        }
    }
    else {
        $response['status_code'] = 400;
    }

    response($response, true);
