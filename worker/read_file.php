<?php

    class ReadFIle
    {
        public function getData ($file_name)
        {
            $output = null;

            try
            {
                $command = escapeshellcmd("python .\main.py $file_name");
                $output = shell_exec($command);
                // echo $command;

            } catch (Exception $e)
            {
                echo $e;
            }

            $json = json_decode($output, true);
//            file_put_contents('PDOErrors.json', $output, FILE_APPEND);

            return $json;
        }
    }