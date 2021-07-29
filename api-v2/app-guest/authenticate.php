<?php

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/account.php';
    include_once dirname(__DIR__, 2) . '/class/crawl_qldt_data.php';
    include_once dirname(__DIR__, 2) . '/class/guest_info.php';
    set_error_handler('exceptions_error_handler');

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($data['username']) &&
        isset($data['password'])) {

        try {
            $crawl = new CrawlQLDTData($data['username'], md5($data['password']));

            switch ($crawl->getStatus()) {
                case -1:
                    $response['status_code'] = 500;
                    break;

                case 0:
                    $response['status_code'] = 401;
                    $response['content']     = 'Invalid Password';
                    break;

                case 1:
                    $response['status_code'] = 200;

                    $info               = $crawl->getStudentInfo();
                    $info['id_student'] = $data['username'];
                    $info['password']   = md5($data['password']);

                    $db      = new Database(false);
                    $connect = $db->connect();

                    $guest_info = new GuestInfo($connect);
                    $guest_info->insert($info);

                    break;
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
