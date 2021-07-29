<?php

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/account.php';
    include_once dirname(__DIR__, 2) . '/class/data_version_student.php';
    include_once dirname(__DIR__, 2) . '/class/data_version_teacher.php';
    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id'])) {

        try {
            $db      = new Database(true);
            $connect = $db->connect();

            $account    = new Account($connect);
            $permission = $account->getAccountPermission($_GET['id']);

            $response['status_code'] = 200;

            switch ($permission) {
                case '0';
                    {
                        $data_version_student = new DataVersionStudent($connect, $_GET['id']);
                        $response['content']  = $data_version_student->getAllDataVersion();

                        break;
                    }

                case '1':
                    {
                        $data_version_teacher = new DataVersionTeacher($connect, $_GET['id']);
                        $response['content']  = $data_version_teacher->getAllDataVersion();

                        break;
                    }

                default:
                    $response['status_code'] = 403;
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