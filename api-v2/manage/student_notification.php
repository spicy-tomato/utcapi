<?php
//
//    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
//    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/notification.php";
//    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/firebase_notification.php";
//    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/worker/read_file.php";
//
//    $response = 'No request';
//
//    $data = json_decode(file_get_contents('php://input'), true);
//
//    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
//        $data != null) {
//
//        $db = new Database();
//        $conn = $db->connect();
//
//        $read_file = new ReadFIle($db);
//        $id_account_list = $read_file->getData($data["file_name"]);
//
//        $helper = new Helper($conn);
//        $id_account_list = $helper->setStudentIdList($data['file_name']);
//
//        $notification = new Notification($conn, $data['info'], $id_account_list);
//
//        $token_list = $helper->getTokenListFromStudentList();
//        $firebase_notification = new FirebaseNotification($data['info'], $token_list);
//
//        if (isset($_POST['time'])) {
//            $notification->setTime($_POST['time']);
//        }
//
//        try {
//            $notification->create();
//
//            $firebase_notification->send();
//
//            $response = 'OK';
//
//        } catch (PDOException $error) {
//            echo json_decode($error);
//
//            $response = 'Failed';
//        }
//    }
//
//    echo json_encode($response);
