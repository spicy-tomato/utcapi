<?php
    include_once dirname(__DIR__) . '/config/db.php';
    include_once dirname(__DIR__) . '/shared/functions.php';
    include_once dirname(__DIR__) . '/class/handle_file.php';
    include_once dirname(__DIR__) . '/class/read_file.php';
    include_once dirname(__DIR__) . '/class/amazon_s3.php';
    include_once dirname(__DIR__) . '/class/student.php';
    include_once dirname(__DIR__) . '/class/participate.php';
    set_error_handler('exceptions_error_handler');
    ini_set('max_execution_time', '300');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            $handleFile = new HandleFile($_FILES);
            $response   = $handleFile->handleFile();

            if ($response != null) {

                $db      = new Database(true);
                $connect = $db->connect();

                $read_file    = new ReadFIle();
                $aws          = new AWS();
                $student      = new Student($connect);
                $participate  = new Participate($connect);

                $location     = dirname(__DIR__) . '/file_upload/';
                foreach ($response as $file_name) {
                    $file_location = $location . $file_name;
                    $data          = $read_file->getData($file_name);

                    $student->insert($data['student_json']);
                    $participate->insert($data['participate_json']);

                    $aws->uploadFile($file_name, $file_location, 'data/');

                }
                $response['status_code'] = 200;
                $response['content']     = 'OK';
            }
            else {
                $response['status_code'] = 204;
                $response['content']     = 'Can Not Read File';
            }

        } catch (Exception $error) {
            printError($error);
            $response['status_code'] = 500;
            $response['content']     = 'Error';
        }
    }
    else {
        $response['status_code'] = 400;
        $response['content']     = 'Invalid Request';
    }

    response($response, true);

