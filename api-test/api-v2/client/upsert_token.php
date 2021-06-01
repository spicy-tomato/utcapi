<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/class/notification.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/class/token.php";

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        !empty($data)) {

        $db      = new Database();
        $connect = $db->connect();

        $token    = new Token($connect, $data['student_id'], $data['token']);
        $response = $token->upsert();
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);
