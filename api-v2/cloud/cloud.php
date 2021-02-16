<?php

    use Kreait\Firebase\Messaging;
    use Kreait\Firebase\Messaging\CloudMessage;

    class Cloud
    {
        private Messaging $messaging;

        public function __construct(Messaging $messaging)
        {
            $this->messaging = $messaging;
        }
    }
