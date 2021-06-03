<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/notification_by_id_account.php';
    include_once dirname(__DIR__, 2) . '/class/account.php';
    include_once dirname(__DIR__, 2) . '/class/data_version.php';
    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id_student']) &&
        isset($_GET['version'])) {

        try {
            $db      = new Database();
            $connect = $db->connect();

            $data_version        = new DataVersion($connect, $_GET['id_student']);
            $latest_data_version = $data_version->getDataVersion('Module_Score');
            $app_data_version    = $_GET['version'];

            if ($latest_data_version != $app_data_version) {
                $account    = new Account($connect);
                $id_account = $account->getIDAccount($_GET['id_student']);

                $notification_by_id_account = new NotificationByIDAccount($connect);
                $response                   = $notification_by_id_account->getAll($id_account);
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
            $response['content']     = 'Error';
        }
    }
    else {
        $response['status_code'] = 406;
        $response['content']     = 'Invalid Request';
    }

    response($response, true);
