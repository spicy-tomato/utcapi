<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/class/account.php';

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        !empty($data)) {

        $db      = new Database();
        $connect = $db->connect();

        $account  = new Account($connect);
        $response = $account->updateQLDTPasswordOfStudentAccount($data['id'], $data['qldt_password']);
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);
