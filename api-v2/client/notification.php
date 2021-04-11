<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/notification_by_id_account.php";

    if ($_SERVER["REQUEST_METHOD"] == 'GET' &&
        isset($_GET["ID"])) {

        $db      = new Database();
        $connect = $db->connect();
        $id      = $_GET["ID"];

        $notification_by_id_account = new NotificationByIDAccount($connect);
        $response                   = $notification_by_id_account->getAll($id);
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);