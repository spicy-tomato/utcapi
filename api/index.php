<?php
    include "../config/csdl.php";
    include "./class/tkb_controller.php";
    include "./class/sinh_vien_controller.php";

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode('/', $uri);

    switch ($uri[4]){
        case 'sv':
            $ma_sv                = $uri[5] ?? "";
            $phuong_thuc_truy_van = $_SERVER['REQUEST_METHOD'];

            $csdl    = new CSDL();
            $ket_noi = $csdl->KetNoi();

            $sinh_vien_controller = new SinhVienController($ket_noi, $phuong_thuc_truy_van, $ma_sv);
            $sinh_vien_controller->xuLyTruyVan();
            break;

        case 'tkb':
            $ma_sv                = $uri[5] ?? "";
            $nam_hoc              = $uri[6] ?? "";
            $hoc_ky               = (int) $uri[7] ?? 0;
            $phuong_thuc_truy_van = $_SERVER['REQUEST_METHOD'];

            $csdl    = new CSDL();
            $ket_noi = $csdl->KetNoi();

            $tkb_controller = new TKBController($ket_noi, $phuong_thuc_truy_van, $ma_sv, $nam_hoc, $hoc_ky);
            $tkb_controller->xuLyTruyVan();
            break;

        default:
            http_response_code(404);
            exit();
    }

