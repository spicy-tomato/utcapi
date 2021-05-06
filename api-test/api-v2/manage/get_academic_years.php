<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . "/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/class/academic_year.php";

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $db      = new Database();
        $connect = $db->connect();

        $academic_year = new AcademicYear($connect);
        $response      = $academic_year->getAcademicYear();
    }
    else {
        $response = 'Invalid Request';
    }

    echo json_encode($response);
