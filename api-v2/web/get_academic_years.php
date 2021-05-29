<?php
    include_once dirname(__DIR__, 2) . '/config/db.php';
    include_once dirname(__DIR__, 2) . '/shared/functions.php';
    include_once dirname(__DIR__, 2) . '/class/academic_year.php';

    $response = [];

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        try {
            $db      = new Database();
            $connect = $db->connect();

            $academic_year = new AcademicYear($connect);
            $response      = $academic_year->getAcademicYear();

        } catch (Exception $error) {
            $response['status_code'] = 500;
            $response['content']     = 'Error';
        }
    }
    else {
        $response['status_code'] = 406;
        $response['content']     = 'Invalid Request';
    }

    response($response, true);
