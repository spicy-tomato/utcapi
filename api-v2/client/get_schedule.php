<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/schedule.php";

    if (isset($_GET['id'])) {
        $db   = new Database();
        $conn = $db->connect();
        $res  = null;
        $tkb  = new Schedule($conn, $_GET['id']);

        if (isset($_GET['start']) &&
            isset($_GET['to'])) {

            $res = $tkb->get($_GET['start'], $_GET['end']);
        }
        else {
            $res = $tkb->getAll();
        }

        echo json_encode($res);
    }
