<?php
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/student_schedule.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/teacher_schedule.php";

    if (isset($_GET['id'])) {
        $db   = new Database();
        $conn = $db->connect();
        $res  = null;

        switch ($_GET['pass'])
        {
            case '0';
            {
                $schedules = new StudentSchedule($conn, $_GET['id']);

                if (isset($_GET['start']) &&
                    isset($_GET['to'])) {

                    $res = $schedules->getByTime($_GET['start'], $_GET['end']);
                }
                else {
                    $res = $schedules->getAll();
                }

                break;
            }

            case '1':
            {
                $schedules = new TeacherSchedule($conn, $_GET['id']);

                if (isset($_GET['start']) &&
                    isset($_GET['to'])) {

                    $res = $schedules->getByTime($_GET['start'], $_GET['end']);
                }
                else {
                    $res = $schedules->getAll();
                }

                break;
            }

        }


        echo json_encode($res);
    }