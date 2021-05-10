<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/shared/functions.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/class/device.php';

    use Kreait\Firebase\Exception\FirebaseException;
    use Kreait\Firebase\Exception\MessagingException;
    use Kreait\Firebase\Factory;
    use Kreait\Firebase\Messaging;
    use Kreait\Firebase\Messaging\CloudMessage;
    use \Kreait\Firebase\Messaging\Notification;

    class FirebaseNotification
    {
        private Messaging $messaging;
        private array $token_list;
        private Notification $notification;
        private string $credentials_path;

        public function __construct (array $info, array $token_list)
        {
            $this->credentials_path = $_SERVER['DOCUMENT_ROOT'] . '/config/firebase_credentials.json';
            $this->_setInfo($info);
            $this->token_list = $token_list;
            $this->_initFactory();
        }

        public function send ()
        {
            $db      = new Database();
            $connect = $db->connect();
            $device  = new Device($connect);

            foreach ($this->token_list as $token) {
                $message = CloudMessage::withTarget('token', $token)
                    ->withNotification($this->notification);

                try {
                    $this->messaging->send($message);
                } catch (MessagingException | FirebaseException $error) {
//                    printError($error);
                    echo $error->getCode();

                    if ($error->getCode() == 400 ||
                        $error->getCode() == 404) {

                        $device->deleteOldToken($token);
                    }
                }
            }
        }

        private function _setInfo (array $info)
        {
            $this->notification = Notification::create(
                $info['title'],
                $info['content']
            );
        }

        private function _initFactory ()
        {
            $factory = new Factory();

            $factory         = $factory->withServiceAccount($this->credentials_path);
            $this->messaging = $factory->createMessaging();
        }
    }
