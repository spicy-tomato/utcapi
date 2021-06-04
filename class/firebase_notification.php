<?php
    require dirname(__DIR__) . '/vendor/autoload.php';
    include_once dirname(__DIR__) . '/shared/functions.php';
    include_once dirname(__DIR__) . '/class/device.php';

    use Kreait\Firebase\Exception\FirebaseException;
    use Kreait\Firebase\Exception\MessagingException;
    use Kreait\Firebase\Factory;
    use Kreait\Firebase\Messaging;
    use Kreait\Firebase\Messaging\CloudMessage;
    use Kreait\Firebase\Messaging\Notification;

    class FirebaseNotification
    {
        private Messaging $messaging;
        private array $token_list;
        private Notification $notification;
        private string $credentials_path;

        public function __construct (array $info, array $token_list)
        {
            $this->credentials_path = dirname(__DIR__) . '/config/firebase_credentials.json';
            $this->_setInfo($info);
            $this->token_list = $token_list;
            $this->_initFactory();
        }

        public function send ()
        {
            $db      = new Database(true);
            $connect = $db->connect();
            $device  = new Device($connect);

            foreach ($this->token_list as $token) {
                $message = CloudMessage::withTarget('token', $token)
                    ->withNotification($this->notification);

                try {
                    $this->messaging->send($message);
                } catch (MessagingException | FirebaseException $error) {
                    printError($error);

                    if ($error->getCode() == 0) {
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
