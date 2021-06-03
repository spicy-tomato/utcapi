<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/exam_schedule.php';
    include_once dirname(__DIR__, 2) . '/class/data_version.php';
    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id_student'])) {

        try {
            $db            = new Database();
            $connect       = $db->connect();
            $exam_schedule = new ExamSchedule($connect, $_GET['id_student']);
            $data_version  = new DataVersion($connect, $_GET['id_student']);

            $response = $exam_schedule->getExamSchedule();
            if ($response['status_code'] == 200) {
                $response['content']['data_version'] = $data_version->getDataVersion('Module_Score');
            }

        } catch (Exception $error) {
            printError($error);
            $response['status_code'] = 500;
            $response['content']     = 'Error';
        }
    }
    else {
        $response['status_code'] = 406;
        $response['content']     = 'Invalid Request';
    }

    response($response, true);
