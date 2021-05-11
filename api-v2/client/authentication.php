<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/class/account.php';

    $account = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        !empty($account)) {

        $db      = new Database();
        $connect = $db->connect();

        $login_app = new Account($connect);
        $response  = $login_app->checkAccount($account);
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);
