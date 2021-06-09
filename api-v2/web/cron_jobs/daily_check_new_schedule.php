<?php
    include_once dirname(__DIR__, 3) . '/config/db.php';
    include_once dirname(__DIR__, 3) . '/shared/functions.php';
    include_once dirname(__DIR__, 3) . '/class/module_class.php';
    include_once dirname(__DIR__, 3) . '/class/data_version.php';
    include_once dirname(__DIR__, 3) . '/worker/amazon_s3.php';
    set_error_handler('exceptions_error_handler');

    try {
        $aws           = new AWS();
        $last_semester = $aws->getDataFromFile('last_semester.txt', 'cron-jobs/');

        $db      = new Database(true);
        $connect = $db->connect();

        $module_Class    = new ModuleClass($connect);
        $newest_semester = $module_Class->getLatestSemester();

        if ($last_semester == $newest_semester) {
            $response['status_code'] = 200;
            $response['content']     = 'No Data Change';
            response($response, true);
        }

        $data_version = new DataVersion($connect);
        $data_version->updateAllScheduleVersion($newest_semester);

        EnvIO::loadEnv();
        $root_folder = $_ENV['LOCAL_ROOT_PROJECT'] ?? '';

        file_put_contents('last_semester.txt', $newest_semester);
        $file_location = $_SERVER['DOCUMENT_ROOT'] . $root_folder . '/api-v2/web/cron_jobs/last_semester.txt';
//        $aws->uploadFile('last_semester.txt', $file_location, 'cron-jobs/');

        $response['status_code'] = 200;
        $response['content']     = 'OK';

    } catch (Exception $error) {
        printError($error);
        $response['status_code'] = 500;
    }

    response($response, true);