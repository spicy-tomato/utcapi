<?php
    session_start();
    include_once dirname(__DIR__, 3) . '/config/db.php';
    include_once dirname(__DIR__, 3) . '/class/module_score.php';
    include_once dirname(__DIR__, 3) . '/class/exam_schedule.php';
    include_once dirname(__DIR__, 3) . '/class/crawl_qldt_data.php';
    include_once dirname(__DIR__, 3) . '/class/account.php';

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($data)) {

        $db           = new Database();
        $connect      = $db->connect();
        $account      = new Account($connect);
        $module_score = new ModuleScore($connect);

        $data['qldt_password'] = $account->getQLDTPasswordOfStudentAccount($data['id_account']);

        $semester = $module_score->getSemester($data['id_student']);

        $crawl      = new CrawlQLDTData($data['id_student'], $data['qldt_password']);
        $crawl_data = $crawl->getStudentExamSchedule($semester);

        if (isset($crawl_data[0])) {
            if ($crawl_data[0] == -1) {
                $response = 'Failed';
            }
            else {
                $response = 'Invalid Password';
            }
        }
        else {
            $response = 'OK';
        }

        if ($response == 'OK') {
            $exam_schedule = new ExamSchedule($connect);
            $response      = $exam_schedule->pushData($crawl_data);
        }
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);
