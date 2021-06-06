<?php
    include_once dirname(__DIR__) . '/config/db.php';
    include_once dirname(__DIR__) . '/shared/functions.php';
    include_once 'handle_file.php';
    include_once 'read_file.php';
    include_once 'push_data_to_database.php';
    include_once 'amazon_s3.php';
    set_error_handler('exceptions_error_handler');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            $handleFile = new HandleFile($_FILES);
            $response   = $handleFile->handleFile();

            if ($response != null) {

                $db      = new Database();
                $connect = $db->connect();

                $read_file    = new ReadFIle();
                $work_with_db = new WorkWithDatabase($connect);
                $aws          = new AWS();

                $location = dirname(__DIR__) . '/file_upload/';
                foreach ($response as $file_name) {
                    $file_location = $location . $file_name;
                    $data          = $read_file->getData($file_name);

                    $aws->uploadFile($file_name, $file_location, 'data/');

                    //                $work_with_db->setData($data['student_json']);
                    //                $work_with_db->pushData('Student');

                    //                $work_with_db->setData();
                    //                $work_with_db->pushData('Module');

                    //                $work_with_db->setData($data['module_class_json']);
                    //                $work_with_db->pushData('Module_Class');
                    //
                    //                $work_with_db->setData($data['participate_json']);
                    //                $work_with_db->pushData('Participate');

                    //                $work_with_db->setData($data['schedule_json']);
                    //                $work_with_db->pushData('Schedules');

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

