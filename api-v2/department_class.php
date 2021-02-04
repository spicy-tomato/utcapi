<?php


include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/csdl.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/DepartmentClass.php";

$db   = new CSDL();
$conn = $db->KetNoi();
$res  = null;
$department_class = new DepartmentClass($conn);


switch ($_SERVER["REQUEST_METHOD"])
{
    case "POST":
        if (isset($_GET["academic_year"]))
        {
            if (isset($_GET["faculty"]))
            {
                $res = $department_class->getALL();
            }
            else
            {
                $res = $department_class->getALLAcademic_Year();
            }
        }
        else
        {
            if (isset($_GET["faculty"]))
            {
                $res = $department_class->getALlFaculty();
            }
            else
            {
                $res = $department_class->get();
            }
        }
        break;

    default:
        echo null;
        die();
}

echo json_encode($res);
