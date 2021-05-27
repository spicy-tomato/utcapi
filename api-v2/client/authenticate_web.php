<?php
    session_start();

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/class/account.php';

    if (isset($_POST['btn-submit'])) {
        $db      = new Database();
        $connect = $db->connect();
        $account = new Account($connect);

        $data['username'] = addslashes(strip_tags($_POST['username']));
        $data['password'] = addslashes(strip_tags($_POST['password']));

        $response = $account->login_web($data);

        if ($response['message'] != 'failed') {
            $_SESSION['account_owner'] = $response['info']['account_owner'];
            $_SESSION['id_account']    = $response['info']['ID'];

            header('Location: ../../ui/home');
        }
        else {
            header('Location: ../../ui/login/index.php?login-failed=true');
        }
    }
