<?php
    session_start();

    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/class/login_info.php';

    $db      = new Database();
    $connect = $db->connect();

    if (isset($_POST['btn-submit'])) {
        $username = addslashes(strip_tags($_POST['username']));
        $password = addslashes(strip_tags($_POST['password']));

        $loginInfo = new LoginInfo($connect, $username, $password);

        if ($loginInfo->login()) {
            $_SESSION['account_owner']       = $loginInfo->getAccountOwner();
            $_SESSION['id_account'] = $loginInfo->getAccountID();

            header('Location: ../home');
        }
        else {
            header('Location: index.php?login-failed=true');
        }
    }
