<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/account.php';
    include_once dirname(__DIR__, 2) . '/class/student_schedule.php';
    include_once dirname(__DIR__, 2) . '/class/teacher_schedule.php';

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id'])) {

        try {
            $db      = new Database();
            $connect = $db->connect();
            $account = new Account($connect);

            $flag = $account->getAccountPermission($_GET['id']);
            switch ($flag) {
                case '0';
                    {
                        $schedules = new StudentSchedule($connect, $_GET['id']);
                        $response  = $schedules->getAll();
                        break;
                    }

                case '1':
                    {
                        $schedules = new TeacherSchedule($connect, $_GET['id']);
                        $response  = $schedules->getAll();
                        break;
                    }

                default:
                    $response['status_code'] = 200;
                    $response['content']     = 'Not Found';
            }

        } catch (Exception $error) {
            $response['status_code'] = 500;
            $response['content']     = 'Error';
        }
    }
    else {
        $response['content']     = 'Invalid Request';
        $response['status_code'] = 406;
    }

    response($response, true);

