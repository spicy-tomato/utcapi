<?php
    include_once dirname(__DIR__) . '/config/db.php';
    include_once dirname(__DIR__) . '/shared/functions.php';
    include_once dirname(__DIR__) . '/class/account.php';
    include_once dirname(__DIR__) . '/class/student.php';
    include_once dirname(__DIR__) . '/class/read_file.php';
    include_once dirname(__DIR__) . '/class/amazon_s3.php';
    include_once dirname(__DIR__) . '/class/participate.php';
    include_once dirname(__DIR__) . '/class/handle_file.php';
    include_once dirname(__DIR__) . '/class/faculty_class.php';
    set_error_handler('exceptions_error_handler');
    ini_set('max_execution_time', '300');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $response['status_code'] = 200;

        try {
            $handleFile  = new HandleFile($_FILES);
            $file_upload = $handleFile->handleFile();

            if ($file_upload != null) {

                $db      = new Database(true);
                $connect = $db->connect();

                $aws       = new AWS();
                $read_file = new ReadFIle();

                $account       = new Account($connect);
                $student       = new Student($connect);
                $participate   = new Participate($connect);
                $data_version  = new DataVersion($connect);
                $faculty_class = new FacultyClass($connect);

                $exception_file_name_list     = [];
                $duplicate_key_file_name_list = [];

                $location = dirname(__DIR__) . '/file_upload/';
                foreach ($file_upload as $old_file_name => $new_file_name) {
                    $file_location = $location . $new_file_name;
                    $data          = $read_file->getData($new_file_name);
                    $flag          = true;

                    $sql_data = $faculty_class->extractData($data['student_json']);

                    $faculty_class->insert();
                    $account->autoCreateStudentAccount($sql_data['account']['arr'], $sql_data['account']['sql']);
                    $student->insert($sql_data['student']['arr'], $sql_data['student']['sql']);
                    $account->bindIDAccountToStudent();
                    $data_version->insert($sql_data['data_version']['arr'], $sql_data['data_version']['sql']);
                    $isDuplicate = $participate->insert($data['participate_json']);

                    if (!empty($data['exception_json'])) {
                        $file_name = '1-' . $old_file_name . '.txt';
                        $title     = 'File excel cùng tên hiện tại có chứa lớp học ko có mã lớp học phần đi kèm:';
                        printFileImportException($file_name, $data['exception_json'], $title);

                        $exception_file_name_list[] = $file_name;

                        $flag = false;
                    }
                    if (!$isDuplicate) {
                        $file_name = '2-' . $old_file_name . '.txt';
                        $title     = 'Cơ sở dữ liệu hiện tại không có một vài mã lớp học phần trong file excel cùng tên này';
                        printFileImportException($file_name, [], $title);

                        $duplicate_key_file_name_list[] = $file_name;

                        $flag = false;
                    }
                    if ($flag) {
                        $aws->uploadFile($new_file_name, $file_location, 'data/');
                        echo 111;
                    }
                }

                if (empty($duplicate_key_file_name_list) &&
                    empty($exception_file_name_list)) {

                    $response['status_code'] = 200;
                    $response['content']     = 'OK';
                }
                else {
                    $response['status_code'] = 201;
                    $response['content']     = array_merge($exception_file_name_list, $duplicate_key_file_name_list);
                }
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

