<?php
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/csdl.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/tkb.php";

    if (isset($_GET['id'])) {
        $db   = new CSDL();
        $conn = $db->KetNoi();
        $res  = null;
        $tkb  = new TKB($conn, $_GET['id']);

        if (isset($_GET['start']) && isset($_GET['to'])) {
            $res = $tkb->hienThi($_GET['start'], $_GET['end']);
        }
        else {
            $res = $tkb->hienThiTatCa();
        }

        echo json_encode($res);
    }