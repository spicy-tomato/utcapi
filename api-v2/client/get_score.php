<?php
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/module_score.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/crawl_qldt_data.php";

    if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
        isset($_GET['id'])) {

        $db      = new Database();
        $connect = $db->connect();

        $module_score = new ModuleScore($connect,);
        $response     = $module_score->getScore($_GET['id']);
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);
