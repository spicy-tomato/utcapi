<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/class/account.php';
    include_once dirname(__DIR__, 2) . '/class/crawl_qldt_data.php';

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        !empty($data)) {

        $db      = new Database();
        $connect = $db->connect();

        $data['qldt_password'] = md5($data['qldt_password']);
        $crawl = new CrawlQLDTData($data['id_student'], $data['qldt_password']);

        if ($crawl->getStatus() == 1) {
            $account  = new Account($connect);
            $response = $account->updateQLDTPasswordOfStudentAccount($data['id_account'], $data['qldt_password']);
        }
        else {
            $response = 'Invalid Password';
        }
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);
