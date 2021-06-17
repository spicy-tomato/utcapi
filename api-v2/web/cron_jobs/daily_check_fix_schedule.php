<?php

    use Kreait\Firebase\Exception\FirebaseException;
    use Kreait\Firebase\Exception\MessagingException;

    include_once dirname(__DIR__, 3) . '/config/db.php';
    include_once dirname(__DIR__, 3) . '/shared/functions.php';
    include_once dirname(__DIR__, 3) . '/class/helper.php';
    include_once dirname(__DIR__, 3) . '/class/device.php';
    include_once dirname(__DIR__, 3) . '/class/amazon_s3.php';
    include_once dirname(__DIR__, 3) . '/class/fix_schedule.php';
    include_once dirname(__DIR__, 3) . '/class/notification.php';
    include_once dirname(__DIR__, 3) . '/class/data_version.php';
    include_once dirname(__DIR__, 3) . '/class/firebase_notification.php';
    include_once dirname(__DIR__, 3) . '/class/notification_by_id_account.php';
    set_error_handler('exceptions_error_handler');

    try {
        $aws                = new AWS();
        $last_time_accepted = $aws->getDataFromFile('last_schedule_fixed.txt', 'cron-jobs/');

        $db      = new Database(true);
        $connect = $db->connect();

        $changes           = new FixSchedule($connect);
        $arr_fix_schedules = $changes->getFixedSchedules($last_time_accepted);

        if (empty($arr_fix_schedules)) {
            $response['status_code'] = 200;
            $response['content']     = 'No Data Change';
            response($response, true);
        }

        foreach ($arr_fix_schedules as $changes) {
            $info['title']      = 'Thay đổi lịch học môn ' . $changes['Module_Name'];
            $info['content']    = $info['title'] . ' từ ngày ' . convertDate($changes['Day_Fix']);
            $info['content']    .= ' sang ca ' . $changes['Shift_Schedules'] . ' ngày ' . convertDate($changes['Day_Schedules']);
            $info['content']    .= ' tại phòng ' . $changes['ID_Room'];
            $info['typez']      = 'study';
            $info['sender']     = $changes['ID_Account'];
            $info['time_start'] = '';
            $info['time_end']   = '';

            $post_form['info'] = $info;

            $helper          = new Helper($connect);
            $id_student_list = $helper->getListFromModuleClassList([$changes['ID_Module_Class']]);
            $id_account_list = $helper->getAccountListFromStudentList($id_student_list);

            $device     = new Device($connect);
            $token_list = $device->getTokenByIdStudent($id_student_list);

            $notification = new Notification($connect);
            $notification->setUpData($info);
            $notification_by_id_account = new NotificationByIDAccount($connect);
            $firebase_notification      = new FirebaseNotification($info, $token_list);
            $data_version               = new DataVersion($connect);

            $id_notification = $notification->insert();
            $notification_by_id_account->pushData($id_account_list, $id_notification);
            $data_version->updateAllNotificationVersion($id_notification);
            $firebase_notification->send();

            if ($changes['Time_Accept_Request'] == $arr_fix_schedules[count($arr_fix_schedules) - 1]['Time_Accept_Request']) {
                EnvIO::loadEnv();
                $root_folder = $_ENV['LOCAL_ROOT_PROJECT'] ?? '';

                file_put_contents('last_schedule_fixed.txt', $changes['Time_Accept_Request']);
                $file_location = $_SERVER['DOCUMENT_ROOT'] . $root_folder . '/api-v2/web/cron_jobs/last_schedule_fixed.txt';
                $aws->uploadFile('last_schedule_fixed.txt', $file_location, 'cron-jobs/');
            }
        }
        $response['status_code'] = 200;
        $response['content']     = 'OK';

    } catch (Error | Exception | MessagingException | FirebaseException $error) {
        printError($error);
        $response['status_code'] = 500;
    }

    response($response, true);
