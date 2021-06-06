<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/notification_by_id_account.php';
    include_once dirname(__DIR__, 2) . '/class/data_version.php';
    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id_student']) &&
        isset($_GET['id_account']) &&
        isset($_GET['version'])) {

        try {
            $db      = new Database(true);
            $connect = $db->connect();

            $data_version        = new DataVersion($connect, $_GET['id_student']);
            $latest_data_version = $data_version->getDataVersion('Notification');

            if ($latest_data_version != intval($_GET['version'])) {
                $notification_by_id_account = new NotificationByIDAccount($connect);
                $response                   = $notification_by_id_account->getAll($_GET['id_account']);

                if ($response['status_code'] == 200) {
                    $response['content']['data_version'] = $latest_data_version;
                }
            }
            else {
                $response['status_code'] = 204;
            }

        } catch (Exception $error) {
            printError($error);
            $response['status_code'] = 500;
        }
    }
    else {
        $response['status_code'] = 400;
    }

    response($response, true);
