<?php
    require dirname(__DIR__, 2) . '/vendor/autoload.php';

    use Kreait\Firebase\Exception\FirebaseException;
    use Kreait\Firebase\Exception\MessagingException;

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/guest_info.php';
    include_once dirname(__DIR__, 2) . '/class/notification_guest.php';
    include_once dirname(__DIR__, 2) . '/class/firebase_notification.php';
    set_error_handler('exceptions_error_handler');

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($data['id_notification']) &&
        isset($data['academic_year']) &&
        isset($data['faculty']) &&
        isset($data['info'])) {

        try {
            $db      = new Database(false);
            $connect = $db->connect();
            $guest   = new GuestInfo($connect);

            $data_guest = $guest->getData($data['faculty'], $data['academic_year']);

            $notification_guest    = new NotificationGuest($connect);
            $firebase_notification = new FirebaseNotification($data['info'], $data_guest['device_token']);

            $notification_guest->insert($data_guest['id_guest'], $data['id_notification']);
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

