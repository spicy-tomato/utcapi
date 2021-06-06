<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/exam_schedule.php';
    include_once dirname(__DIR__, 2) . '/class/data_version.php';
    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id_student']) &&
        isset($_GET['version'])) {

        try {
            $db_main      = new Database(true);
            $connect_main = $db_main->connect();
            $db_extra      = new Database(false);
            $connect_extra = $db_extra->connect();

            $data_version        = new DataVersion($connect_main, $_GET['id_student']);
            $latest_data_version = $data_version->getDataVersion('Exam_Schedule');

            if ($latest_data_version != intval($_GET['version'])) {
                $exam_schedule = new ExamSchedule($connect_extra, $_GET['id_student']);
                $response      = $exam_schedule->getExamSchedule();

                if ($response['status_code'] == 200) {
                    $response['content']['data_version'] = $latest_data_version;
                }
            }
            else {
                $response['status_code'] = 204;
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
