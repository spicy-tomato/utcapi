<?php

    require dirname(__DIR__) . '/vendor/autoload.php';
    include_once dirname(__DIR__) . '/shared/functions.php';
    include_once dirname(__DIR__) . '/class/device.php';

    use Kreait\Firebase\Factory;
    use Kreait\Firebase\Messaging;
    use Kreait\Firebase\Messaging\AndroidConfig;
    use Kreait\Firebase\Messaging\CloudMessage;
    use Kreait\Firebase\Exception\FirebaseException;
    use Kreait\Firebase\Exception\MessagingException;

    class FirebaseNotification
    {
        private Messaging $messaging;
        private array $token_list;
        private AndroidConfig $config;
        private string $credentials_path;

        public function __construct (array $info, array $token_list)
        {
            $this->credentials_path = dirname(__DIR__) . '/config/firebase_credentials.json';
            $this->token_list       = $token_list;
            $this->_setConfig($info);
            $this->_initFactory();
        }

        /**
         * @throws MessagingException
         * @throws FirebaseException
         */
        public function send ()
        {
            $db      = new Database(true);
            $connect = $db->connect();
            $device  = new Device($connect);

            $invalid_tokens = [];
            $tokens_num     = count($this->token_list);
            $recent_index   = 0;

            $message = CloudMessage::withTarget('token', 'all')
                ->withAndroidConfig($this->config);

            while ($recent_index < $tokens_num) {
                try {
                    $arr_length         = $recent_index + 500 <= $tokens_num ? 500 : $tokens_num - $recent_index;
                    $token_prepare_list = array_slice($this->token_list, $recent_index, $arr_length);

                    $report       = $this->messaging->sendMulticast($message, $token_prepare_list);
                    $recent_index += $arr_length;

                    if ($report->hasFailures()) {
                        $temp_invalid_tokens = array_merge($report->invalidTokens(), $report->unknownTokens());
                        $invalid_tokens      = array_merge($invalid_tokens, $temp_invalid_tokens);
                    }

                } catch (MessagingException | FirebaseException $error) {
                    throw $error;
                }
            }

            $device->deleteOldTokens($invalid_tokens);
        }

        private function _setConfig (array $info)
        {
            $this->config = AndroidConfig::fromArray([
                'ttl' => '172800s',
                'priority' => 'high',
                'notification' => [
                    'title' => $info['title'],
                    'body' => $info['content'],
                ],
            ]);
        }

        private function _initFactory ()
        {
            $factory         = new Factory();
            $factory         = $factory->withServiceAccount($this->credentials_path);
            $this->messaging = $factory->createMessaging();
        }
    }
