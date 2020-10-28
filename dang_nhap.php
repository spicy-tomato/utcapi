<?php
    include "./config/csdl.php";
    session_start();

    if (isset($_POST['login'])){

        $csdl    = new CSDL();
        $ket_noi = $csdl->KetNoi();

        $ma_sv    = $_POST['ma_sv'];
        $mat_khau = $_POST['mat_khau'];

        $sinh_vien = new SinhVien($ket_noi, $ma_sv);

        if ($sinh_vien->dangNhap($mat_khau)){
            $_SESSION['ma_sv'] = $ma_sv;
        }
    }