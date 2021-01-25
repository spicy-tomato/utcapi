<?php
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/csdl.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/tkb.php";

    if (isset($_GET['id'])) {
        $db   = new CSDL();
        $conn = $db->KetNoi();
        $res  = null;
        $tkb  = new TKB($conn, $_GET['id']);

        if (isset($_GET['start']) && isset($_GET['to'])) {
            $res = $tkb->hienThi($_GET['start'], $_GET['end']);
        }
        else {
            $res = $tkb->hienThiTatCa();
        }

        echo json_encode($res);
    }

//        if (isset($_SESSION['ma_sv'])) {
//            $db   = new CSDL();
//            $conn = $db->KetNoi();
//            $res  = null;
//            $tkb  = new TKB($conn, $_SESSION['ma_sv']);
//
//            if (isset($_GET['start']) && isset($_GET['to'])) {
//                $res = $tkb->hienThi($_GET['start'], $_GET['end']);
//            }
//    //        else if (isset($_GET['start']) && !isset($_GET['end'])){
//    //          $res = $tkb->hienThi($_GET['start'], )
//    //        }
//            else {
//                $res = $tkb->hienThiTatCa();
//            }
//
////            echo json_encode($res);
//
//            ?>
<!--      <br><br><br>-->
<!--      <table>-->
<!--        <tr>-->
<!--          <td>Mã môn</td>-->
<!--          <td>Tên môn</td>-->
<!--          <td>Phòng</td>-->
<!--          <td>Ca</td>-->
<!--          <td>Ngày</td>-->
<!--        </tr>-->
<!--          --><?php
//                  foreach ($res as $item) {
//
//                      ?>
<!--                <tr>-->
<!--                    --><?php
//                            foreach ($item as $i) {
//                              if ($i != ''){
//                                ?>
<!--                          <td>--><?php //echo $i ?><!--</td>-->
<!--                            --><?php
//                              }
//                            }
//                        ?>
<!--                </tr>-->
<!--                  --><?php
//                  }
//              ?>
<!--      </table>-->
<!--        --><?php
//        } else {
//            header('Location: index.php');
//        }
?>