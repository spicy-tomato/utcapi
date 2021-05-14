<?php
    include_once dirname(__DIR__) . '/utils/env_io.php';

    function printError (Exception $error)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date    = date("d/m/Y H:i:s");
        $message = $date . "\n" . $error->getCode() . "\n" . $error->getMessage() . "\n";
        EnvIO::loadEnv();
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $_ENV['ROOT_PROJECT'] . '/Errors.txt', $message, FILE_APPEND);
    }

    function convertDate ($date) : string
    {
        $arr  = explode('-', $date);
        $date = $arr[2] . '-' . $arr[1] . '-' . $arr[0];

        return $date;
    }