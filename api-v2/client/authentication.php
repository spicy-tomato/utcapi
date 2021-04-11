<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/utcapi/config/db.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/utcapi/class/login_app_info.php';

    $account = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        !empty($account)) {

        $db      = new Database();
        $connect = $db->connect();

        $login_app = new LoginApp($connect);
        $response  = $login_app->checkAccount($account);
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);
