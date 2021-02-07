<?php
    session_start();

    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/login_info.php";

    $db   = new Database();
    $conn = $db->connect();

    if (isset($_POST['btn-submit'])) {
        $id       = addslashes(strip_tags($_POST['id']));
        $loginInfo = new LoginInfo($conn, $id, '');

        if ($loginInfo->login()) {
            $_SESSION['ma_sv'] = $loginInfo->getMaSv();
            unset($_SESSION['login-failed']);
            header('Location: schedule.php');
        } else {
            header('Location: index.php?login-failed=true');
        }
    }
