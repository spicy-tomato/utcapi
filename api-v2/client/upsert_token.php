<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/notification.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/token.php";

    $response = 'No request';

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        $data != null) {

        $db   = new Database();
        $conn = $db->connect();

        $token = new Token($conn, $data['student_id'], $data['token']);
        $response = $token->upsert() ? 'OK' : 'Failed';
    }

    echo json_encode($response);
