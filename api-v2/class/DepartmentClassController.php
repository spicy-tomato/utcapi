<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/utcapi/config/csdl.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/utcapi/class/department_class.php";

class DepartmentClassController
{
    private PDO    $ket_noi;
    private string $phuong_thuc_truy_van;

    private DepartmentClass $department_class;

    public function __construct (PDO $ket_noi, string $phuong_thuc_truy_van)
    {
        $this->ket_noi              = $ket_noi;
        $this->phuong_thuc_truy_van = $phuong_thuc_truy_van;

        $this->department_class = new DepartmentClass($ket_noi);
    }

    public function xuLyTruyVan () : void
    {
        switch ($this->phuong_thuc_truy_van){
            case "POST":
                if (isset($_GET["academic_year"]))
                {
                    if (isset($_GET["faculty"]))
                    {
                        $phan_hoi = $this->getALL();
                    }
                    else
                    {
                        $phan_hoi = $this->getALLAcademic_Year();
                    }
                }
                else
                {
                    if (isset($_GET["faculty"]))
                    {
                        $phan_hoi = $this->getALlFaculty();
                    }
                    else
                    {
                        $phan_hoi = $this->get();
                    }
                }
                break;

            default:
                echo 3;
                $phan_hoi = $this->khongTimThay();
                break;
        }
        header($phan_hoi['trang_thai']);
        if ($phan_hoi['noi_dung']){
            echo $phan_hoi['noi_dung'];
        }
    }

    public function getALL()
    {
        $du_lieu = $this->department_class->getALL();

        $phan_hoi['trang_thai'] = $_SERVER['SERVER_PROTOCOL'] . ' 200 OK';
        $phan_hoi['noi_dung']   = json_encode($du_lieu);
        echo $phan_hoi['trang_thai'];
        return $phan_hoi;
    }

    public function getALLAcademic_Year()
    {
        $du_lieu = $this->department_class->getALLAcademic_Year();

        $phan_hoi['trang_thai'] = $_SERVER['SERVER_PROTOCOL'] . ' 200 OK';
        $phan_hoi['noi_dung']   = json_encode($du_lieu);

        return $phan_hoi;
    }

    public function getALlFaculty()
    {
        $du_lieu = $this->department_class->getALlFaculty();

        $phan_hoi['trang_thai'] = $_SERVER['SERVER_PROTOCOL'] . ' 200 OK';
        $phan_hoi['noi_dung']   = json_encode($du_lieu);

        return $phan_hoi;
    }

    public function get()
    {
        $du_lieu = $this->department_class->get();

        $phan_hoi['trang_thai'] = $_SERVER['SERVER_PROTOCOL'] . ' 200 OK';
        $phan_hoi['noi_dung']   = json_encode($du_lieu);

        return $phan_hoi;
    }

    public function khongTimThay () : array
    {
        $phan_hoi['trang_thai'] = $_SERVER['SERVER_PROTOCOL'] . ' 404 Not found';
        $phan_hoi['noi_dung']   = null;

        return $phan_hoi;
    }

    public function khongPhanHoi () : array
    {
        $phan_hoi['trang_thai'] = $_SERVER['SERVER_PROTOCOL'] . ' 422 Unprocessable Entity';
        $phan_hoi['noi_dung']   = json_encode(
            [ 'error' => 'Invalid input' ]
        );

        return $phan_hoi;
    }

    public function xacNhan (array $du_lieu) : bool
    {
        return
            isset($du_lieu['ma_sv']) ||
            isset($du_lieu['mat_khau']) ||
            isset($du_lieu['ho_ten']) ||
            isset($du_lieu['ma_lop']);
    }
}