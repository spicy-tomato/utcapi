<?php
//    include_once "../config/db.php";
//    include_once "./class/tkb_controller.php";
//    include_once "./class/sinh_vien_controller.php";
//    include_once $_SERVER["DOCUMENT_ROOT"] . "/utcapi/api-v2/class/DepartmentClassController.php";
//
//header("Access-Control-Allow-Origin: *");
//    header("Content-Type: application/json; charset=UTF-8");
//    header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
//    header("Access-Control-Max-Age: 3600");
//    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//
//    ini_set('display_errors', 1);
//    ini_set('display_startup_errors', 1);
//    error_reporting(E_ALL);
//
//    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//    $uri = explode('/', $uri);
//
//    switch ($uri[4]){
//        case 'sv':
//            $ma_sv                = $uri[5] ?? "";
//            $phuong_thuc_truy_van = $_SERVER['REQUEST_METHOD'];
//
//            $csdl    = new Database();
//            $connect = $csdl->connect();
//
//            $sinh_vien_controller = new SinhVienController($connect, $phuong_thuc_truy_van, $ma_sv);
//            $sinh_vien_controller->xuLyTruyVan();
//            break;
//
//        case 'tkb':
//            $ma_sv                = $uri[5] ?? "";
//            $nam_hoc              = $uri[6] ?? "";
//            $hoc_ky               = (int) $uri[7] ?? 0;
//            $phuong_thuc_truy_van = $_SERVER['REQUEST_METHOD'];
//
//            $csdl    = new Database();
//            $connect = $csdl->connect();
//
//            $tkb_controller = new TKBController($connect, $phuong_thuc_truy_van, $ma_sv, $nam_hoc, $hoc_ky);
//            $tkb_controller->xuLyTruyVan();
//            break;
//
//        case 'getclass':
//            $phuong_thuc_truy_van = $_SERVER['REQUEST_METHOD'];
//
//            $csdl    = new Database();
//            $connect = $csdl->connect();
//
//            $department_class_controller = new DepartmentClassController($connect, $phuong_thuc_truy_van);
//            $department_class_controller->xuLyTruyVan();
//            break;
//
//
//        default:
//            http_response_code(404);
//            exit();
//    }
//
