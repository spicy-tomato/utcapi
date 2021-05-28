<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/class/notification_by_id_account.php';
    include_once dirname(__DIR__, 2) . '/class/account.php';

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id'])) {

        $db                         = new Database();
        $connect                    = $db->connect();
        $account                    = new Account($connect);
        $notification_by_id_account = new NotificationByIDAccount($connect);

        $id         = $_GET['id'];
        $id_account = $account->getIDAccount($id);
        $response   = $notification_by_id_account->getAll($id_account);
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);
