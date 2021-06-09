<?php

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/account.php';
    include_once dirname(__DIR__, 2) . '/class/student_schedule.php';
    include_once dirname(__DIR__, 2) . '/class/teacher_schedule.php';
    include_once dirname(__DIR__, 2) . '/class/data_version.php';
    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id'])) {

        try {
            $db      = new Database(true);
            $connect = $db->connect();

            $account    = new Account($connect);
            $permission = $account->getAccountPermission($_GET['id']);

            $data_version     = new DataVersion($connect, $_GET['id']);
            $schedule_version = $data_version->getDataVersion('Schedule');

            switch ($permission) {
                case '0';
                    {
                        $schedules = new StudentSchedule($connect, $_GET['id']);
                        $data      = $schedules->getAllSchedule();

                        break;
                    }

                case '1':
                    {
                        $schedules = new TeacherSchedule($connect, $_GET['id']);
                        $data      = $schedules->getAllSchedule();

                        break;
                    }

                default:
                    $response['status_code'] = 403;
            }

            if (isset($data) && empty($data)) {
                $response['status_code'] = 204;
            }
            else {
                if (!isset($response['status_code'])) {
                    $response['status_code']             = 200;
                    $response['content']['data']         = $data;
                    $response['content']['data_version'] = $schedule_version;
                }
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

