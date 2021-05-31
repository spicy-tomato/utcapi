<?php
    include_once 'shared/functions.php';
    //    ini_set('display_errors', 0);
    //    ini_set('display_startup_errors', 0);
    //    error_reporting(E_ALL);
    //    echo $a;
    set_error_handler('exceptions_error_handler');


    //    function exceptions_error_handler ($severity, $message, $filename, $lineno)
    //    {
    //        if (error_reporting() == 0) {
    //            return;
    //        }
    //        if (error_reporting() & $severity) {
    //            throw new ErrorException($message, 0, $severity, $filename, $lineno);
    //        }
    //    }

    $a[1] = 'jfksjfks';
    try {
        $b = $a[0];
    } catch (Exception $e) {
        echo $e->getMessage();
        echo $e->getFile();
        echo $e->getLine();
    }