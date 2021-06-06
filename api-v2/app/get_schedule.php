<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/account.php';
    include_once dirname(__DIR__, 2) . '/class/student_schedule.php';
    include_once dirname(__DIR__, 2) . '/class/teacher_schedule.php';
    include_once dirname(__DIR__, 2) . '/class/data_version.php';
    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id']) &&
        isset($_GET['version'])) {

        try {
            $db      = new Database(true);
            $connect = $db->connect();

            $data_version        = new DataVersion($connect, $_GET['id']);
            $latest_data_version = $data_version->getDataVersion('Schedule');

            if ($latest_data_version != intval($_GET['version'])) {
                $account    = new Account($connect);
                $permission = $account->getAccountPermission($_GET['id']);

                switch ($permission) {
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
                        $response['status_code'] = 400;
                        $response['content']     = 'Not Found Data';
                }

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

