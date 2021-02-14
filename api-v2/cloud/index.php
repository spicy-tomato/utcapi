<?php
    require $_SERVER['DOCUMENT_ROOT'] .'/utcapi/vendor/autoload.php';

    use \Kreait\Firebase\Messaging\CloudMessage;
    use \Kreait\Firebase\Messaging\Notification;
    use \Kreait\Firebase\Factory;


//    $title = "New title";
//    $body  = "New body";
//
//    $notification = Notification::fromArray([
//        'title' => $title,
//        'body' => $body,
//    ]);

    $credentials_path = './firebase_credentials.json';

    $factory = new Factory();
    $factory = $factory->withServiceAccount($credentials_path);

    var_dump($factory);

    $messaging = $factory->createMessaging();

    $deviceToken = 'eSpMoC6wSomYA8fTeIuulD:APA91bENgkeozjiQxIPutoZl4Lae7CDZfB9bT4mXWTBkG67d_mNxUtNqCtJbeg_COt7hbXmaY-PZlO4fBzO5cFKdjVnocxaSpiJ6sagWfusxbT79Td93Bwi-k02EiEtw-NhRTGHWtpyY';

    $message = CloudMessage::withTarget('token', $deviceToken)
        ->withNotification(Notification::create(
            'New title 2021',
            'New body Tan Suu'
        ))
        ->withData(['key' => 'value']);


    try {
        $messaging->send($message);
    } catch (\Kreait\Firebase\Exception\MessagingException $e) {
        echo "Messaging Exception";
    } catch (\Kreait\Firebase\Exception\FirebaseException $e) {
        echo "Firebase Exception";
    }

