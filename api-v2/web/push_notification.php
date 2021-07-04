<?php
    require dirname(__DIR__, 2) . '/vendor/autoload.php';

    use Kreait\Firebase\Exception\FirebaseException;
    use Kreait\Firebase\Exception\MessagingException;

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/helper.php';
    include_once dirname(__DIR__, 2) . '/class/device.php';
    include_once dirname(__DIR__, 2) . '/class/data_version.php';
    include_once dirname(__DIR__, 2) . '/class/notification.php';
    include_once dirname(__DIR__, 2) . '/class/notification_account.php';
    include_once dirname(__DIR__, 2) . '/class/firebase_notification.php';
    set_error_handler('exceptions_error_handler');

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($data['class_list']) &&
        isset($data['info'])) {

        try {
            $db      = new Database(true);
            $connect = $db->connect();
            $helper  = new Helper($connect);

            if ($data['target'] == 'fc') {
                $id_student_list = $helper->getListFromFacultyClass($data['class_list']);
            }
            else {
                $id_student_list = $helper->getListFromModuleClassList($data['class_list']);
            }

            $id_account_list = $helper->getAccountListFromStudentList($id_student_list);

            $device     = new Device($connect);
            $token_list = $device->getTokenByIdStudent($id_student_list);

            $notification          = new Notification($connect);
            $notification_account  = new NotificationAccount($connect);
            $firebase_notification = new FirebaseNotification($data['info'], $token_list);
            $data_version          = new DataVersion($connect);

            $notification->setUpData($data['info']);
            $id_notification = $notification->insert();
            $notification_account->pushData($id_account_list, $id_notification);
            $data_version->updateAllNotificationVersion($id_notification);
            $firebase_notification->send();

            $response['status_code'] = 200;
            $response['content']     = 'OK';

        } catch (Error | Exception | MessagingException | FirebaseException $error) {
            printError($error);
            $response['status_code'] = 500;
        }
    }
    else {
        $response['status_code'] = 400;
    }

    response($response, true);

