<?php
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/config/db.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/class/account.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/class/student_schedule.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/class/teacher_schedule.php';

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id'])) {

        $db      = new Database();
        $connect = $db->connect();
        $account = new Account($connect);

        $flag = $account->getPermission($_GET['id']);
        switch ($flag) {
            case '0';
                {
                    $schedules = new StudentSchedule($connect, $_GET['id']);
                    $response       = $schedules->getAll();
                    break;
                }

            case '1':
                {
                    $schedules = new TeacherSchedule($connect, $_GET['id']);
                    $response       = $schedules->getAll();
                    break;
                }

            default:
                $response = [];
        }
    }
    else {
        $response = 'Invalid Request';

    }

    echo json_encode($response);
