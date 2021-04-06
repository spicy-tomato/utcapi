<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/print_error.php";

    class ReadFIle
    {
        public function getData ($file_name)
        {
            $output = null;

            try
            {
                $command = escapeshellcmd("python .\main.py $file_name");
                $output = shell_exec($command);

            } catch (Exception $e)
            {
                printError($e);;
            }

            $json = json_decode($output, true);

            return $json;
        }
    }