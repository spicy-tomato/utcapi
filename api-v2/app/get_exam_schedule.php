<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/class/exam_schedule.php';

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id'])) {

        $db      = new Database();
        $connect = $db->connect();

        $exam_schedule = new ExamSchedule($connect);
        $response      = $exam_schedule->getExamSchedule($_GET['id']);
        if (empty($response)) {
            $response = 'Not Found';
        }
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);
