<?php

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/class/notification_by_id_account.php';

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id'])) {

        $db      = new Database();
        $connect = $db->connect();
        $id      = $_GET['id'];

        $notification_by_id_account = new NotificationByIDAccount($connect);
        $response                   = $notification_by_id_account->getAll($id);
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);