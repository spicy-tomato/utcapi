<?php
    session_start();

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/account.php';
    set_error_handler('exceptions_error_handler');

    $response = [];

    if (isset($_POST['btn-submit'])) {
        try {
            $db      = new Database(true);
            $connect = $db->connect();
            $account = new Account($connect);

            $data['username'] = addslashes(strip_tags($_POST['username']));
            $data['password'] = addslashes(strip_tags($_POST['password']));
            $account_info     = $account->login($data);

            if (!$account_info ||
                !password_verify($data['password'], $account_info['password'])) {

                $response['status_code'] = 401;
                $response['content']     = 'Invalid Account';
            }
            else {
                switch ($account_info['permission']) {
                    case '1':
                        $response = $account->getDataAccountOwner($account_info['id'], 'Teacher');
                        if ($response['status_code'] == 200) {
                            $response['content']['account_owner'] = 'Gv. ' . $response['content']['Name_Teacher'];
                        }
                        break;

                    case '2':
                        $response = $account->getDataAccountOwner($account_info['id'], 'Department');
                        if ($response['status_code'] == 200) {
                            $response['content']['account_owner'] = $response['content']['Department_Name'];
                        }
                        break;

                    case '3':
                        $response = $account->getDataAccountOwner($account_info['id'], 'Faculty');
                        if ($response['status_code'] == 200) {
                            $response['content']['account_owner'] = $response['content']['Faculty_Name'];
                        }
                        break;

                    case '4':
                        $response = $account->getDataAccountOwner($account_info['id'], 'Other_Department');
                        if ($response['status_code'] == 200) {
                            $response['content']['account_owner'] = $response['content']['Other_Department_Name'];
                        }
                        break;

                    default:
                        $response['status_code'] = 401;
                        $response['content']     = 'Invalid Account';
                }
            }

            if ($response['status_code'] == 200) {
                $_SESSION['account_owner'] = $response['content']['account_owner'];
                $_SESSION['id_account']    = $response['content']['ID'];

                header('Location: ../../ui/home');
            }
            else {
                header('Location: ../../ui/login/index.php?login-failed=true');
            }

        } catch (Error | Exception $error) {
            printError($error);
            $response['status_code'] = 500;

            header('Location: ../../ui/login/index.php?login-failed=true');

        }
    }