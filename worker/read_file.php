<?php
    include_once dirname(__DIR__) . '/shared/functions.php';

    class ReadFIle
    {
        public function getData ($file_name)
        {
            try {
                $command = escapeshellcmd('python main.py ' . $file_name);
                $output  = shell_exec($command);

            } catch (Exception $error) {
                printError($error);

                return null;
            }

            $json = json_decode($output, true);

            return $output != null ? $json : null;
        }
    }