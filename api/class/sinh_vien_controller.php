<?php
//    include_once "./../class/sinh_vien.php";
//
//    class SinhVienController
//    {
//        private PDO    $connect;
//        private string $ma_sv;
//        private string $phuong_thuc_truy_van;
//
//        private SinhVien $sinh_vien;
//
//        public function __construct (PDO $connect, string $phuong_thuc_truy_van, string $ma_sv)
//        {
//            $this->connect              = $connect;
//            $this->phuong_thuc_truy_van = $phuong_thuc_truy_van;
//            $this->ma_sv                = $ma_sv;
//
//            $this->sinh_vien = new SinhVien($connect);
////            echo($ma_sv);
//        }
//
//        public function xuLyTruyVan () : void
//        {
//            switch ($this->phuong_thuc_truy_van){
//                case 'GET':
//                    if ($this->ma_sv){
//                        $phan_hoi = $this->layThongTin($this->ma_sv);
//                    }
//                    else {
//                        $phan_hoi = $this->layThongTinToanBo();
//                    }
//                    break;
//
//                case 'POST':
//                    $phan_hoi = $this->themSinhVien();
//                    break;
//
//                case 'PUT':
//                    $phan_hoi = $this->capNhatSinhVien($this->ma_sv);
//                    break;
//
//                case 'DELETE':
//                    $phan_hoi = $this->xoaSinhVien($this->ma_sv);
//                    break;
//
//                default:
//                    $phan_hoi = $this->khongTimThay();
//                    break;
//            }
//
//            header($phan_hoi['trang_thai']);
//            if ($phan_hoi['noi_dung']){
//                echo $phan_hoi['noi_dung'];
//            }
//        }
//
//        public function layThongTinToanBo () : array
//        {
//            $du_lieu = $this->sinh_vien->timTatCa();
//            if (!$du_lieu){
//                return $this->khongTimThay();
//            }
//
//            $phan_hoi['trang_thai'] = $_SERVER['SERVER_PROTOCOL'] . ' 200 OK';
//            $phan_hoi['noi_dung']   = json_encode($du_lieu);
//
//            return $phan_hoi;
//        }
//
//        public function layThongTin (string $ma_sv) : array
//        {
//            $du_lieu = $this->sinh_vien->tim($ma_sv);
//            if (!$du_lieu){
//                return $this->khongTimThay();
//            }
//
//            $phan_hoi['trang_thai'] = $_SERVER['SERVER_PROTOCOL'] . ' 200 OK';
//            $phan_hoi['noi_dung']   = json_encode($du_lieu);
//
//            return $phan_hoi;
//        }
//
//        public function themSinhVien () : array
//        {
//            $du_lieu_vao = (array) json_decode(file_get_contents('php://input'), true);
//            if (!$this->xacNhan($du_lieu_vao)){
//                return $this->khongPhanHoi();
//            }
//
//            $this->sinh_vien->them($du_lieu_vao);
//
//            $phan_hoi['trang_thai'] = $_SERVER['SERVER_PROTOCOL'] . ' 201 Created';
//            $phan_hoi['noi_dung']   = null;
//
//            return $phan_hoi;
//        }
//
//        public function capNhatSinhVien (string $ma_sv) : array
//        {
//            $du_lieu = $this->sinh_vien->tim($ma_sv);
//            if (!$du_lieu){
//                return $this->khongTimThay();
//            }
//
//            $du_lieu_vao = (array) json_decode(file_get_contents('php://input'), true);
//            if (!$this->xacNhan($du_lieu_vao)){
//                return $this->khongPhanHoi();
//            }
//
//            $this->sinh_vien->capNhat($ma_sv, $du_lieu_vao);
//
//            $phan_hoi['trang_thai'] = $_SERVER['SERVER_PROTOCOL'] . ' 200 OK';
//            $phan_hoi['noi_dung']   = null;
//
//            return $phan_hoi;
//        }
//
//        public function xoaSinhVien (string $ma_sv) : array
//        {
//            $du_lieu = $this->sinh_vien->tim($ma_sv);
//            if (!$du_lieu){
//                return $this->khongTimThay();
//            }
//
//            $this->sinh_vien->xoa($ma_sv);
//
//            $phan_hoi['trang_thai'] = $_SERVER['SERVER_PROTOCOL'] . ' 200 OK';
//            $phan_hoi['noi_dung']   = null;
//
//            return $phan_hoi;
//        }
//
//        public function khongTimThay () : array
//        {
//            $phan_hoi['trang_thai'] = $_SERVER['SERVER_PROTOCOL'] . ' 404 Not found';
//            $phan_hoi['noi_dung']   = null;
//
//            return $phan_hoi;
//        }
//
//        public function khongPhanHoi () : array
//        {
//            $phan_hoi['trang_thai'] = $_SERVER['SERVER_PROTOCOL'] . ' 422 Unprocessable Entity';
//            $phan_hoi['noi_dung']   = json_encode(
//                [ 'error' => 'Invalid input' ]
//            );
//
//            return $phan_hoi;
//        }
//
//        public function xacNhan (array $du_lieu) : bool
//        {
//            return
//                isset($du_lieu['ma_sv']) ||
//                isset($du_lieu['mat_khau']) ||
//                isset($du_lieu['ho_ten']) ||
//                isset($du_lieu['ma_lop']);
//        }
//    }