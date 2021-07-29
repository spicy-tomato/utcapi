<?php

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/guest_info.php';
    include_once dirname(__DIR__, 2) . '/class/notification.php';
    include_once dirname(__DIR__, 2) . '/class/notification_guest.php';

    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id_guest'])) {

        try {
            $db           = new Database(true);
            $connect_main = $db->connect();

            $db            = new Database(false);
            $connect_extra = $db->connect();

            $guest_info         = new GuestInfo($connect_extra);
            $notification_guest = new NotificationGuest($connect_extra);

            $data  = [];
            $data2 = [];

            if (isset($_GET['id_notification'])) {
                $id_notification_list = $notification_guest->getIDNotification($_GET['id_guest']);
                $notification_guest->setConnect($connect_main);
                $data = $notification_guest->getAllNotification($id_notification_list, $_GET['id_notification']);

                $notification = new Notification($connect_main);
                $data2        = $notification->getDeletedNotification();
            }
            else {
                $id_notification_list = $notification_guest->getIDNotification($_GET['id_guest']);
                $notification_guest->setConnect($connect_main);
                $data = $notification_guest->getAllNotification($id_notification_list);
            }

            $notification_version = $guest_info->getNotificationVersion($_GET['id_guest']);

            if (empty($data) && empty($data2)) {
                $response['status_code'] = 204;
            }
            else {
                $response['status_code']                  = 200;
                $response['content']['data']              = $data;
                $response['content']['data']['index_del'] = $data2;
                $response['content']['data_version']      = $notification_version;
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
