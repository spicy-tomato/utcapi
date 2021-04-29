<?php
//    include_once "../class/student_schedule.php";
//
//    class TKBController
//    {
//        private PDO    $connect;
//        private string $ma_sv;
//        private string $nam_hoc;
//        private string $hoc_ky;
//        private string $phuong_thuc_truy_van;
//
//        private Schedule $tkb;
//
//        public function __construct (PDO $connect, string $phuong_thuc_truy_van, string $ma_sv, string $nam_hoc, int $hoc_ky)
//        {
//            $this->connect              = $connect;
//            $this->phuong_thuc_truy_van = $phuong_thuc_truy_van;
//            $this->ma_sv                = $ma_sv;
//            $this->nam_hoc              = $nam_hoc;
//            $this->hoc_ky               = $hoc_ky;
//
//            $this->tkb = new Schedule($connect);
//        }
//
//        public function xuLyTruyVan () : void
//        {
//            if ($this->phuong_thuc_truy_van == 'GET' &&
//                $this->ma_sv &&
//                $this->nam_hoc &&
//                $this->hoc_ky){
//
//                $phan_hoi = $this->layTKB();
//            }
//            else {
//                $phan_hoi = $this->khongTimThay();
//            }
//
//            header($phan_hoi['trang_thai']);
//            if ($phan_hoi['noi_dung']){
//                echo $phan_hoi['noi_dung'];
//            }
//        }
//
//        public function layTKB () : array
//        {
//            // Loi o day, hay sua TKB::hienThi(...)
//
//            $du_lieu = $this->tkb->hienThi($this->ma_sv, $this->nam_hoc, $this->hoc_ky);
//            if (!$du_lieu){
//                return $this->khongTimThay();
//            }
//
//            $phan_hoi['trang_thai'] = $_SERVER['SERVER_PROTOCOL'] . " 200 OK";
//            $phan_hoi['noi_dung']   = json_encode($du_lieu);
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
//    }