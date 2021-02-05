<?php


include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/department_class.php";

$db   = new Database();
$conn = $db->connect();
$res  = null;
$department_class = new DepartmentClass($conn);


switch ($_SERVER["REQUEST_METHOD"])
{
    case "POST":
        if (isset($_GET["academic_year"]))
        {
            if (isset($_GET["faculty"]))
            {
                $res = $department_class->getAll();
            }
            else
            {
                $res = $department_class->getAllAcademic_Year();
            }
        }
        else
        {
            if (isset($_GET["faculty"]))
            {
                $res = $department_class->getAllFaculty();
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
