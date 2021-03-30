<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/worker/handle_file.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/worker/read_file.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/worker/push_data_to_database.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";

    $response = 'No request';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $handleFile = new HandleFile($_FILES);
        $response = $handleFile->handleFile();

        if ($_POST['flag'] == 1 ||
            $response != 'Failure')
        {
            $db = new Database();
            $read_file = new ReadFIle();
            $work_with_db = new WorkWithDatabase($db);

            foreach ($response as $file_name)
            {
                $data = $read_file->getData($file_name);
                $str = json_encode($response);

//                $work_with_db->setData($data['student_json']);
//                $work_with_db->pushData("Student");

//                $work_with_db->setData($data['module_json']);
//                $work_with_db->pushData("Module");

//                $work_with_db->setData($data['module_class_json']);
//                $work_with_db->pushData("Module_Class");
//
//                $work_with_db->setData($data['participate_json']);
//                $work_with_db->pushData("Participate");

                $work_with_db->setData($data['schedule_json']);
                $work_with_db->pushData("Schedules");

            }
            $response = 'OK';
        }
    }

    echo json_encode($response);

