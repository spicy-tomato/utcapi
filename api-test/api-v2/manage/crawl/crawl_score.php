<?php
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/module_score.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/crawl_qldt_data.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/account.php";

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($data)) {

        $db      = new Database();
        $connect = $db->connect();
        $account = new Account($connect);

        if (!isset($data['qldt_password'])) {
            $data['qldt_password'] = $account->getQLDTPasswordOfStudentAccount($data['id_account']);
        }
        else {
            $data['qldt_password'] = md5($data['qldt_password']);
            $account->updateQLDTPasswordOfStudentAccount($data['id_account'], $data['qldt_password']);
        }

        $crawl      = new CrawlQLDTData($data['id_student'], $data['qldt_password']);
        $crawl_data = $crawl->getAll();
        $response   = empty($crawl_data) ? 'Failed' : 'OK';

        if ($response == 'OK') {
            $module_score = new ModuleScore($connect);
            $module_score->pushData($crawl_data);
        }
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);
