<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/notification_by_id_account.php';
    include_once dirname(__DIR__, 2) . '/class/account.php';
    include_once dirname(__DIR__, 2) . '/class/data_version.php';
    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id_student'])) {

        try {
            $db                         = new Database();
            $connect                    = $db->connect();
            $account                    = new Account($connect);
            $notification_by_id_account = new NotificationByIDAccount($connect);
            $data_version               = new DataVersion($connect, $_GET['id_student']);

            $id_account = $account->getIDAccount($_GET['id_student']);
            $response   = $notification_by_id_account->getAll($id_account);
            if ($response['status_code'] == 200) {
                $response['content']['data_version'] = $data_version->getDataVersion('Module_Score');
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
