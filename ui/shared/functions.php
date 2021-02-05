<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/csdl.php";


    function shared_header(string $title): void
    {
        echo '
        <head>
          <meta charset="UTF-8">
          <meta name="viewport"
                  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
          <meta http-equiv="X-UA-Compatible" content="ie=edge">
          <link rel="stylesheet" href="css/style.css">
          <script src="https://kit.fontawesome.com/9ed593a796.js" crossorigin="anonymous"></script>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
                  integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
          <title>' . $title . '</title>
        </head>';
    }


    function shared_navbar(): void
    {
        $nav1_class  = 'nav-link';
        $nav2_class  = 'nav-link';
        $nav3_class  = 'nav-link';
        $current_nav = ' active';

        $home_link  = '/utcapi/ui/home';
        $form1_link = '/utcapi/ui/forms/deparment-class';
        $form2_link = '/utcapi/ui/forms/module-class';
        $form3_link = '/utcapi/ui/forms/student';

        if (stripos($_SERVER['REQUEST_URI'], 'deparment-class') !== false) {
            $nav1_class .= $current_nav;
        } else if (stripos($_SERVER['REQUEST_URI'], 'module-class') !== false) {
            $nav2_class .= $current_nav;
        } else if (stripos($_SERVER['REQUEST_URI'], 'student') !== false) {
            $nav3_class .= $current_nav;
        }

        echo '
  <header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark px-5">
      <a href="' . $home_link . '" class="navbar-brand">Trang chủ</a>
      <div class="collapse navbar-collapse d-flex justify-content-between">
        <ul class="navbar-nav">
          <li class="navbar-item">
            <a href="' . $form1_link . '" class="' . $nav1_class . '">Khoá - Khoa - Lớp</a>
          </li>
          <li class="navbar-item">
            <a href="' . $form2_link . '" class="' . $nav2_class . '">Lớp học phần</a>
          </li>
          <li class="navbar-item">
            <a href="' . $form3_link . '" class="' . $nav3_class . '">Sinh viên</a>
          </li>
        </ul>

        <div class="dropdown">
          <button type="button"
                  class="btn btn-secondary dropdown-toggle"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                  id="dropdownMenuButton">
            ' . $_SESSION["department_name"] . '
            <i class="fas fa-cogs"></i>
          </button>
          <ul class="dropdown-menu mr-5" aria-labelledby="dropdownMenuButton">
            <li><a href="" class="dropdown-item">Cài đặt</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a href="/utcapi/ui/home/logout.php" class="dropdown-item">Đăng xuất</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <div class="mt-4 mb-4" style="height: 50px"></div>';
    }

    function shared_form(): void
    {
        echo '
        <div class="form-group">
          <label for="title"><legend>Tiêu đề:</legend></label>
          <input type="text" class="form-control" id="title">
        </div>
        <div class="form-group mt-4">
          <label for="content"><legend>Nội dung:</legend></label>
          <textarea id="content" cols="30" rows="10" class="form-control"></textarea>
        </div>';
    }

    function shield()
    {
        if (!isset($_SESSION['department_name'])) {
            header('Location: ' . $_SERVER['DOCUMENT_ROOT'] . '/utcapi/ui/login');
        }
    }


    function getAllModuleClass(){
        $db   = new CSDL();
        $conn = $db->KetNoi();


    }
