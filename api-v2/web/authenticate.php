<?php
    session_start();

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/account.php';

    $response = [];

    if (isset($_POST['btn-submit'])) {
        try {
            $db      = new Database();
            $connect = $db->connect();
            $account = new Account($connect);

            $data['username'] = addslashes(strip_tags($_POST['username']));
            $data['password'] = addslashes(strip_tags($_POST['password']));

            $account_info = $account->login($data);
            if ($account_info == 'Failed') {
                $response['status_code']        = 200;
                $response['content']['message'] = 'failed';
            }
            else {
                switch ($account_info['permission']) {
                    case '1':
                        $response = $account->getDataAccountOwner($account_info['id'], 'Teacher');
                        if ($response['content']['message'] == 'success') {
                            $response['content']['info']['account_owner'] = 'Gv.' . $response['content']['info']['Name_Teacher'];
                        }
                        break;

                    case '2':
                        $response = $account->getDataAccountOwner($account_info['id'], 'Department');
                        if ($response['content']['message'] == 'success') {
                            $response['content']['info']['account_owner'] = $response['content']['info']['Department_Name'];
                        }
                        break;

                    case '3':
                        $response = $account->getDataAccountOwner($account_info['id'], 'Faculty');
                        if ($response['content']['message'] == 'success') {
                            $response['content']['info']['account_owner'] = $response['content']['info']['Faculty_Name'];
                        }
                        break;

                    case '4':
                        $response = $account->getDataAccountOwner($account_info['id'], 'Other_Department');
                        if ($response['content']['message'] == 'success') {
                            $response['content']['info']['account_owner'] = $response['content']['info']['Other_Department_Name'];
                        }
                        break;

                    default:
                        $response['status_code']        = 200;
                        $response['content']['message'] = 'failed';
                }
            }

            if ($response['content']['message'] == 'success') {
                $_SESSION['account_owner'] = $response['content']['info']['account_owner'];
                $_SESSION['id_account']    = $response['content']['info']['ID'];

                response($response, false);
                header('Location: ../../ui/home');
            }
            else {
                response($response, false);
                header('Location: ../../ui/login/index.php?login-failed=true');
            }

        } catch (Exception $error) {
            $response['status_code'] = 500;
            $response['content']     = 'Error';

            response($response, false);
            header('Location: ../../ui/login/index.php?login-failed=true');
        }
    }