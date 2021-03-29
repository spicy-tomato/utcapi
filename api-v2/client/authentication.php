<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/login_app_info.php";

    $response = 'No request';

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $db = new Database();
        $connect = $db->connect();

        $login_app = new LoginApp($connect);

        $response = $login_app->checkAccount();
    }

    echo json_encode($response);
