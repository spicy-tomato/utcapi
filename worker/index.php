
<?php
//    echo file_get_contents('http://utcapi.herokuapp.com/api-v2/manage/get_department_class.php');
//
//?>
<!doctype html>
<html>

<head>
  <title>Document</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="description" content="Demo Project">
  <meta name="viewport" content="width=device-width, initial-scale=1">


  <style>
    table,
    th,
    td {
      border: 1px solid black;
      border-collapse: collapse;
    }

    h1 {
      text-transform: uppercase;
    }

    #fixed {
      position: fixed;
      right: 0;
      top: 0;
    }

    #fixed>a {
      font-size: 25px;
    }
  </style>
</head>

<body>
  <div id="fixed">
    <a href="#student">Sinh viên</a>
    <a href="#module-class">Lớp học phần</a>
    <a href="#module">Học phần</a>
    <a href="#participate">Sinh viên - lớp học phần</a>
    <a href="#schedule">Lịch học</a>
  </div>
  <?php

  $output = null;

  try {
    $command = escapeshellcmd("python main.py data.xls");
    $output = shell_exec($command);
  } catch (Exception $e) {
    echo $e;
    exit();
  }

  $json = json_decode($output, true);

//   var_dump($json);

  $student_json = $json['student_json'];
  $module_class_json = $json['module_class_json'];
  $module_json = $json['module_json'];
  $participate_json = $json['participate_json'];
  $schedule_json = $json['schedule_json'];
  echo json_encode($module_json);
  ?>

  <h1 id="student">Sinh viên</h1>
  <table>
    <thead>
      <td></td>
      <td>Mã sinh viên</td>
      <td>Họ tên</td>
      <td>Mã lớp</td>
      <td>Ngày sinh</td>
    </thead>
    <?php
    $i = 1;
    foreach ($student_json as $row) {
    ?>
      <tr>
        <td><?php echo $i ?></td>
        <td><?php echo $row['ID_Student'] ?></td>
        <td><?php echo $row['Student_Name'] ?></td>
        <td><?php echo $row['ID_Class'] ?></td>
        <td><?php echo $row['DoB'] ?></td>
      </tr>
    <?php
      $i++;
    }
    ?>
  </table>

  <h1 id="module-class">Lớp học phần</h1>
  <table>
    <thead>
      <td></td>
      <td>Mã lớp học phần</td>
      <td>Tên lớp học phần</td>
      <td>Mã học phần</td>
      <td>Năm học</td>
    </thead>
    <?php
    $i = 1;
    foreach ($module_class_json as $row) {
    ?>
      <tr>
        <td><?php echo $i ?></td>
        <td><?php echo $row['ID_Module_Class'] ?></td>
        <td><?php echo $row['Module_Class_Name'] ?></td>
        <td><?php echo $row['ID_Module'] ?></td>
        <td><?php echo $row['School_Year'] ?></td>
      </tr>
    <?php
      $i++;
    }
    ?>
  </table>

  <h1 id="module">Học phần</h1>
  <table>
    <thead>
      <td></td>
      <td>Mã học phần</td>
    </thead>
    <?php
    $i = 1;
    foreach ($module_json as $row) {
    ?>
      <tr>
        <td><?php echo $i ?></td>
        <td><?php echo $row ?></td>
      </tr>
    <?php
      $i++;
    }
    ?>
  </table>

  <h1 id="participate">Sinh viên - lớp học phần</h1>
  <table>
    <thead>
      <td></td>
      <td>Mã sinh viên</td>
      <td>Mã lớp học phần</td>
    </thead>
    <?php
    $i = 1;
    foreach ($participate_json as $row) {
    ?>
      <tr>
        <td><?php echo $i ?></td>
        <td><?php echo $row['ID_Student'] ?></td>
        <td><?php echo $row['ID_Module_Class'] ?></td>
      </tr>
    <?php
      $i++;
    }
    ?>
  </table>

  <h1 id="schedule">Lịch học</h1>
  <table>
    <thead>
      <td></td>
      <td>Mã lớp học phần</td>
      <td>Mã phòng</td>
      <td>Ca</td>
      <td>Ngày học</td>
      <td>Số sinh viên</td>
    </thead>
    <?php
    $i = 1;
    foreach ($schedule_json as $row) {
    ?>
      <tr>
        <td><?php echo $i ?> </td>
        <td><?php echo $row['ID_Module_Class'] ?></td>
        <td><?php echo $row['ID_Room'] ?></td>
        <td><?php echo $row['Shift_Schedules'] ?></td>
        <td><?php echo $row['Day_Schedules'] ?></td>
        <td><?php echo $row['Number_Student'] ?></td>
      </tr>
    <?php
      $i++;
    }
    ?>
  </table>
</body>

</html>