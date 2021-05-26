<?php
    include_once dirname(__DIR__, 3) . '/config/db.php';
    include_once dirname(__DIR__, 3) . '/class/notification.php';
    include_once dirname(__DIR__, 3) . '/worker/amazon_s3.php';
    include_once dirname(__DIR__, 3) . '/class/firebase_notification.php';
    include_once dirname(__DIR__, 3) . '/class/helper.php';
    include_once dirname(__DIR__, 3) . '/class/fix_schedule.php';
    include_once dirname(__DIR__, 3) . '/shared/functions.php';

    $aws        = new AWS();
    $last_time_accepted = $aws->getDataFromFile('last_schedule_fixed.txt', 'cron-jobs/');

    $db      = new Database();
    $connect = $db->connect();

    $changes           = new FixSchedule($connect);
    $arr_fix_schedules = $changes->getFixedSchedules($last_time_accepted);

    if (empty($arr_fix_schedules) ||
        $arr_fix_schedules == 'Failed') {

        exit();
    }

    foreach ($arr_fix_schedules as $changes) {
        $info['title']      = 'Thay đổi lịch học môn ' . $changes['Module_Name'];
        $info['content']    = $info['title'] . ' từ ngày ' . convertDate($changes['Day_Fix']);
        $info['content']    .= ' sang ca ' . $changes['Shift_Schedules'] . ' ngày ' . convertDate($changes['Day_Schedules']);
        $info['content']    .= ' tại phòng ' . $changes['ID_Room'];
        $info['typez']      = 'study';
        $info['sender']     = $changes['ID'];
        $info['time_start'] = '';
        $info['time_end']   = '';

        $helper = new Helper($connect);
        $helper->getListFromModuleClassList([$changes['ID_Module_Class']]);

        $id_account_list = $helper->getAccountListFromStudentList();
        $notification    = new Notification($connect, $info, $id_account_list);

        $token_list            = $helper->getTokenListFromStudentList();
        $firebase_notification = new FirebaseNotification($info, $token_list);

        try {
            $notification->create();
            $firebase_notification->send();
            $response = 'OK';

            if ($changes['Time_Accept_Request'] == $arr_fix_schedules[count($arr_fix_schedules) - 1]['Time_Accept_Request']) {
                EnvIO::loadEnv();
                $root_folder   = $_ENV['LOCAL_ROOT_PROJECT'] ?? '';
                $file_location = $_SERVER['DOCUMENT_ROOT'] . $root_folder . '/api-v2/manage/cron_jobs/last_schedule_fixed.txt';
                $aws->uploadFile('last_schedule_fixed.txt', $file_location, 'cron-jobs/');
            }


        } catch (Exception $error) {
            printError($error);

            $response = 'Failed';
        }
    }

    echo json_encode($response);

