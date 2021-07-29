<?php

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/guest_info.php';
    include_once dirname(__DIR__, 2) . '/class/crawl_qldt_data.php';
    set_error_handler('exceptions_error_handler');

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($data['id_student']) &&
        isset($data['qldt_password'])) {

        try {
            $db      = new Database(true);
            $connect = $db->connect();

            $data['qldt_password'] = md5($data['qldt_password']);
            $crawl                 = new CrawlQLDTData($data['id_student'], $data['qldt_password']);

            if ($crawl->getStatus() == 1) {
                $guest_info = new GuestInfo($connect);
                $guest_info->updatePassword($data['id_student'], $data['qldt_password']);

                $response['status_code'] = 201;
                $response['content']     = 'OK';
            }
            else {
                $response['status_code'] = 401;
                $response['content']     = 'Invalid Password';
            }

        } catch (Error | Exception $error) {
            printError($error);
            $response['status_code'] = 500;
        }
    }
    else {
        $response['status_code'] = 400;
    }

    response($response, true);
