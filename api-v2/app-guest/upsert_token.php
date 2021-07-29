<?php

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/guest_info.php';

    set_error_handler('exceptions_error_handler');

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($data['id_student']) &&
        isset($data['token'])) {

        try {
            $db      = new Database(true);
            $connect = $db->connect();

            $guest_info = new GuestInfo($connect);
            $guest_info->upsertToken($data['id_student'], $data['token']);

            $response['status_code'] = 200;
            $response['content']     = 'OK';

        } catch (Error | Exception $error) {
            printError($error);
            $response['status_code'] = 500;
        }
    }
    else {
        $response['status_code'] = 400;
    }

    response($response, true);