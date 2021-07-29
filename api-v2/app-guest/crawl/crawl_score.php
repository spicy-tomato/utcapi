<?php

    include_once dirname(__DIR__, 3) . '/config/db.php';
    include_once dirname(__DIR__, 3) . '/shared/functions.php';
    include_once dirname(__DIR__, 3) . '/class/guest_info.php';
    include_once dirname(__DIR__, 3) . '/class/module_score.php';
    include_once dirname(__DIR__, 3) . '/class/crawl_qldt_data.php';
    set_error_handler('exceptions_error_handler');

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($data['all']) &&
        isset($data['id_student'])) {

        try {
            $db_extra      = new Database(false);
            $connect_extra = $db_extra->connect();

            $guest_info            = new GuestInfo($connect_extra);
            $data['qldt_password'] = $guest_info->getQLDTPassword($data['id_student']);

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
                    $module_score = new ModuleScore($connect_extra, $data['id_student'], true);
                    $crawl_data   = $crawl->getStudentModuleScore($data['all']);

                    if ($data['all'] == 'true') {
                        $module_score->pushAllData($crawl_data);
                    }
                    else {
                        $module_score->pushData($crawl_data);
                    }

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
