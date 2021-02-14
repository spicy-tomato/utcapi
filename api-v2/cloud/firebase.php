<?php

    use Kreait\Firebase\Factory;

    $factory = (new Factory)->withServiceAccount('./firebase_credentials.json');
    global $factory;
