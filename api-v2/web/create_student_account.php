<?php

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/account.php';
    set_error_handler('exceptions_error_handler');

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        !empty($data)) {

        try {
            $db      = new Database(true);
            $connect = $db->connect();

            $part_of_sql_account = '';
            $sql_account_data    = [];
            foreach ($data as $student) {
                $sql_account_data[]  = $student['id_student'];
                $sql_account_data[]  = password_hash($student['dob'], PASSWORD_DEFAULT);
                $part_of_sql_account .= '(?,null,?,null,0),';
            }
            $part_of_sql_account = rtrim($part_of_sql_account, ',');

            $account = new Account($connect);
            $account->autoCreateStudentAccount($sql_account_data, $part_of_sql_account);
            $account->bindIDAccountToStudent();

            $response['status_code'] = 200;
            $response['content']     = 'OK';

        } catch (Error | Exception $error) {
            printError($error);
            $response['status_code'] = 500;
        }
    }
    else {
        $response['status_code'] = 400;
    }

    response($response, true);