<?php
    include_once 'handle_file.php';
    include_once 'read_file.php';
    include_once 'push_data_to_database.php';
    include_once 'amazon_s3.php';
    include_once dirname(__DIR__) . '/config/db.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $handleFile = new HandleFile($_FILES);
        $response   = $handleFile->handleFile();

        if ($_POST['flag'] == 1 ||
            $response != null) {

            $db      = new Database();
            $connect = $db->connect();

            $read_file    = new ReadFIle();
            $work_with_db = new WorkWithDatabase($connect);
            $aws          = new AWS();

            $location = dirname(__DIR__) . '/file_upload/';
            foreach ($response as $file_name) {
                $file_location = $location . $file_name;
                $data = $read_file->getData($file_name);
                unset($data['student_json']);

                //                $aws->uploadFile($file_name, $file_location, 'data/');
                $a =  $data['hjghj'];
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
            $response = 'OK';
        }
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);

