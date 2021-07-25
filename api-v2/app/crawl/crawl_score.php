<?php

    include_once dirname(__DIR__, 3) . '/config/db.php';
    include_once dirname(__DIR__, 3) . '/class/account.php';
    include_once dirname(__DIR__, 3) . '/shared/functions.php';
    include_once dirname(__DIR__, 3) . '/class/module_score.php';
    include_once dirname(__DIR__, 3) . '/class/crawl_qldt_data.php';
    include_once dirname(__DIR__, 3) . '/class/data_version_student.php';
    set_error_handler('exceptions_error_handler');

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($data['all']) &&
        isset($data['id_student']) &&
        isset($data['id_account'])) {

        try {
            $db_main       = new Database(true);
            $connect_main  = $db_main->connect();
            $db_extra      = new Database(false);
            $connect_extra = $db_extra->connect();

            $account               = new Account($connect_main);
            $data['qldt_password'] = $account->getQLDTPasswordOfStudentAccount($data['id_account']);

            $crawl = new CrawlQLDTData($data['id_student'], $data['qldt_password']);

            switch ($crawl->getStatus()) {
                case -1:
                    $response['status_code'] = 500;
                    break;

                case 0:
                    $response['status_code'] = 401;
                    $response['content']     = 'Invalid Password';
                    break;

                case 1:
                    $module_score = new ModuleScore($connect_extra, $data['id_student']);
                    $crawl_data   = $crawl->getStudentModuleScore($data['all']);

                    if ($data['all'] == 'true') {
                        $module_score->pushAllData($crawl_data);
                    }
                    else {
                        $module_score->pushData($crawl_data);
                    }

                    $data_version_student = new DataVersionStudent($connect_main, $data['id_student']);
                    $data_version_student->updateDataVersion('Module_Score');

                    $response['status_code'] = 200;
                    $response['content']     = 'OK';
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
