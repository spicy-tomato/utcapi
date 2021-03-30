<?php
    session_start();

    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/login_info.php";

    $db   = new Database();
    $conn = $db->connect();

    if (isset($_POST['btn-submit'])) {
        $username = addslashes(strip_tags($_POST['username']));
        $password = addslashes(strip_tags($_POST['password']));

        $loginInfo = new LoginInfo($conn, $username, $password);

        if ($loginInfo->login()) {
            $_SESSION['department_name'] = $loginInfo->getDepartmentName();
            $_SESSION['department_id_account'] = $loginInfo->getAccountID();
            header('Location: ../home');
        }
        else {
            header('Location: index.php?login-failed=true');
        }
    }
