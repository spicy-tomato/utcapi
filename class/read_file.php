<?php
    include_once dirname(__DIR__) . '/shared/functions.php';
    ini_set('max_execution_time', '300');

    class ReadFIle
    {
        public function getData ($file_name)
        {
            try {
                $command = escapeshellcmd('python main.py ' . $file_name);
                $output  = shell_exec($command);

            } catch (Exception $error) {
                throw  $error;
            }

            $json = json_decode($output, true);

            return $output != null ? $json : null;
        }
    }