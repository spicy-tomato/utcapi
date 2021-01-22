<?php
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/csdl.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/class/tkb.php";

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Schedule</title>
  <style>
    table {
        border-collapse: collapse;
    }

    table td {
        border: 1px solid #333;
        padding: 3px;
    }
  </style>
</head>
<body>
<form action="tkb.php" method="post">
  <input type="date" name="from">
  <input type="date" name="to">
  <button type="submit">Submit</button>
</form>

<?php

    if (isset($_SESSION['ma_sv'])) {
        $db   = new CSDL();
        $conn = $db->KetNoi();
        $res  = null;
        $tkb  = new TKB($conn, $_SESSION['ma_sv']);

        if (isset($_GET['start']) && isset($_GET['to'])) {
            $res = $tkb->hienThi($_GET['start'], $_GET['end']);
        }
//        else if (isset($_GET['start']) && !isset($_GET['end'])){
//          $res = $tkb->hienThi($_GET['start'], )
//        }
        else {
            $res = $tkb->hienThiTatCa();
        }

        ?>
      <br><br><br>
      <table>
        <tr>
          <td>ID</td>
          <td>Mã môn</td>
          <td>Phòng</td>
          <td>Ca</td>
          <td>Ngày</td>
          <td>Mã SV</td>
          <td>Họ tên</td>
          <td>Lớp</td>
        </tr>
          <?php
              foreach ($res as $item) {

                  ?>
                <tr>
                    <?php
                        foreach ($item as $i) {
                          if ($i != ''){
                            ?>
                          <td><?php echo $i ?></td>
                            <?php
                          }
                        }
                    ?>
                </tr>
                  <?php
              }
          ?>
      </table>
        <?php
    } else {
        header('Location: index.php');
    }
?>
</body>
</html>