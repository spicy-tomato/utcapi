<?php

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

        private const credentials_path = './firebase_credentials.json';

        public function __construct(array $info, array $token_list)
        {
            $this->_setInfo($info);
            $this->token_list = $token_list;
            $this->_initFactory();
        }

        public function send()
        {
            foreach ($this->token_list as $token) {
                $message = CloudMessage::withTarget('token', $token)
                    ->withNotification($this->notification);

                try {
                    $this->messaging->send($message);
                } catch (MessagingException $e) {
                    echo "Messaging Exception";
                } catch (FirebaseException $e) {
                    echo "Firebase Exception";
                }
            }
        }

        private function _setInfo(array $info)
        {
            $this->notification = Notification::create(
                $info['title'],
                $info['content']
            );
        }

        private function _initFactory()
        {
            $factory = new Factory();
            $factory->withServiceAccount(self::credentials_path);

            $this->messaging = $factory->createMessaging();
        }
    }
