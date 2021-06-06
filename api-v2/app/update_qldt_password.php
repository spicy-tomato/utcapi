<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/account.php';
    include_once dirname(__DIR__, 2) . '/class/crawl_qldt_data.php';
    set_error_handler('exceptions_error_handler');

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($data['id_account']) &&
        isset($data['id_student']) &&
        isset($data['qldt_password'])) {

        try {
            $db      = new Database(true);
            $connect = $db->connect();

            $data['qldt_password'] = md5($data['qldt_password']);
            $crawl                 = new CrawlQLDTData($data['id_student'], $data['qldt_password']);

            if ($crawl->getStatus() == 1) {
                $account  = new Account($connect);
                $response = $account->updateQLDTPasswordOfStudentAccount($data['id_account'], $data['qldt_password']);
            }
            else {
                $response['status_code'] = 401;
                $response['content']     = 'Invalid Password';
            }

        } catch (Exception $error) {
            printError($error);
            $response['status_code'] = 500;
        }
    }
    else {
        $response['status_code'] = 400;
    }

    response($response, true);
