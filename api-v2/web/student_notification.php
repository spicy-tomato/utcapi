<?php
//
//    include_once $_SERVER['DOCUMENT_ROOT'] . "/config/db.php";
//    include_once $_SERVER['DOCUMENT_ROOT'] . "/class/notification.php";
//    include_once $_SERVER['DOCUMENT_ROOT'] . "/class/firebase_notification.php";
//    include_once $_SERVER['DOCUMENT_ROOT'] . "/worker/read_file.php";
//
//    $response = 'No request';
//
//    $data = json_decode(file_get_contents('php://input'), true);
//
//    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
//        $data != null) {
//
//        $db = new Database();
//        $connect = $db->connect();
//
//        $read_file = new ReadFIle($db);
//        $id_account_list = $read_file->getData($data["file_name"]);
//
//        $helper = new Helper($connect);
//        $id_account_list = $helper->setStudentIdList($data['file_name']);
//
//        $notification = new Notification($connect, $data['info'], $id_account_list);
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
