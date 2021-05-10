<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . '/config/db.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/class/notification.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/worker/amazon_s3.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/class/firebase_notification.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/class/helper.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/class/fix_schedule.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/shared/functions.php';

    $aws = new AWS();
    $old_id_fix = $aws->getDataFromFile('id_fix.txt');

    $db      = new Database();
    $connect = $db->connect();

    $fix     = new FixSchedule($connect);
    $arr_fix = $fix->getFixSchedules($old_id_fix);

    if (empty($arr_fix) ||
        $arr_fix == 'Failed') {

        exit();
    }

    foreach ($arr_fix as $fix) {
        $info['title']      = 'Thay đổi lịch học môn ' . $fix['Module_Name'];
        $info['content']    = $info['title'] . ' từ ngày ' . convertDate($fix['Day_Schedules']);
        $info['content']    .= ' sang ca ' . $fix['Shift_Fix'] . ' ngày ' . convertDate($fix['Day_Fix']);
        $info['content']    .= ' tại phòng ' . $fix['ID_Room'];
        $info['typez']      = 'study';
        $info['sender']     = $fix['ID'];
        $info['time_start'] = '';
        $info['time_end']   = '';

        $helper = new Helper($connect);
        $helper->getListFromModuleClassList([$fix['ID_Module_Class']]);

        $id_account_list = $helper->getAccountListFromStudentList();
        $notification    = new Notification($connect, $info, $id_account_list);

        $token_list            = $helper->getTokenListFromStudentList();
        $firebase_notification = new FirebaseNotification($info, $token_list);

        try {
            $notification->create();
            $firebase_notification->send();
            $response = 'OK';

            file_put_contents('id_fix.txt', $fix['ID_Fix']);
            $file_location = $_SERVER['DOCUMENT_ROOT'] . '/api-v2/manage/cron_jobs/id_fix.txt';
            $aws->uploadFile('id_fix.txt', $file_location);

        } catch (Exception $error) {
            printError($error);

            $response = 'Failed';
        }
    }

    echo json_encode($response);

