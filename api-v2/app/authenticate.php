<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/account.php';
    set_error_handler('exceptions_error_handler');

    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($data['username']) &&
        isset($data['password'])) {

        try {
            $db      = new Database(true);
            $connect = $db->connect();

            $account      = new Account($connect);
            $account_info = $account->login($data);

            if (!$account_info ||
                !password_verify($data['password'], $account_info['password'])) {

                $response['status_code'] = 401;
                $response['content']     = 'Invalid Account';
            }
            else {
                switch ($account_info['permission']) {
                    case '0':
                        $response = $account->getDataAccountOwner($account_info['id'], 'Student');
                        break;

                    case '1':
                        $response = $account->getDataAccountOwner($account_info['id'], 'Teacher');
                        break;

                    default:
                        $response['status_code'] = 401;
                        $response['content']     = 'Invalid Account';
                }
            }

        } catch (Exception $error) {
            printError($error);
            $response['status_code']        = 500;
        }
    }
    else {
        $response['status_code'] = 400;
    }

    response($response, true);
