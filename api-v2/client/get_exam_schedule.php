<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/class/exam_schedule.php';

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id'])) {

        $db      = new Database();
        $connect = $db->connect();

        $module_score = new ExamSchedule($connect);
        $response     = $module_score->getExamSchedule($_GET['id']);
        if (empty($response)) {
            $response = 'Not Found';
        }
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);
