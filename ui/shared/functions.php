<?php
    include_once dirname(__DIR__, 2) . '/utils/env_io.php';

    EnvIO::loadEnv();

    function shared_header (string $title, string $otherTags = '') : void
    {
        $root_folder = $_ENV['LOCAL_ROOT_PROJECT'] ?? '';

        echo '
        <head>
          <meta charset="UTF-8">
          <meta name="viewport"
                  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
          <meta http-equiv="X-UA-Compatible" content="ie=edge">
          <script src="https://kit.fontawesome.com/9ed593a796.js" crossorigin="anonymous"></script>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
                  integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
          <title>' . $title . '</title>
          ' . $otherTags . '
          <link rel="stylesheet" href="css/style.css">
          <link rel="stylesheet" href="' . $root_folder . '/ui/css/style.css">
        </head>';
    }


    function shared_navbar () : void
    {
        $nav1_class  = 'nav-link';
        $nav2_class  = 'nav-link';
        $nav3_class  = 'nav-link';
        $current_nav = ' active';

        EnvIO::loadEnv();
        $root_folder = $_ENV['LOCAL_ROOT_PROJECT'] ?? '';

        $home_link      = $root_folder . '/ui/home';
        $form1_link     = $root_folder . '/ui/forms/department-class';
        $form2_link     = $root_folder . '/ui/forms/module-class';
        $push_data_link = $root_folder . '/ui/push-data';
        $log_out_link   = $root_folder . '/ui/home/logout.php';

        if (stripos($_SERVER['REQUEST_URI'], 'department-class') !== false) {
            $nav1_class .= $current_nav;
        }
        elseif (stripos($_SERVER['REQUEST_URI'], 'module-class') !== false) {
            $nav2_class .= $current_nav;
        }
        elseif (stripos($_SERVER['REQUEST_URI'], 'push-data') !== false) {
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
            <a href="' . $push_data_link . '" class="' . $nav3_class . '">Nhập dữ liệu</a>
          </li>
        </ul>

        <div class="dropdown">
          <button type="button"
                  class="btn btn-secondary dropdown-toggle"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                  id="dropdownMenuButton">
            ' . $_SESSION["account_owner"] . '
            <i class="fas fa-cogs"></i>
          </button>
          <ul class="dropdown-menu mr-5" aria-labelledby="dropdownMenuButton">
            <li><a href="" class="dropdown-item">Cài đặt</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a href="' . $log_out_link . '" class="dropdown-item">Đăng xuất</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <div class="mt-4 mb-4" style="height: 50px"></div>';
    }

    function shared_form () : void
    {
        echo '
        <div class="form__">
          <div class="left">
            <div class="form-group">
              <label for="title"><legend>Tiêu đề:</legend></label>
              <input type="text" class="form-control" id="title">
            </div>
            <div class="form-group">
              <label for="title"><legend>Thể loại:</legend></label><br>
              <select name="type" id="type">
                <option value="study">Học tập</option>
                <option value="fee">Học phí</option>
                <option value="extracurricular">Ngoại khóa</option>
                <option value="social_payment">Chi trả xã hội</option>
                <option value="others">Thông báo khác</option>
              </select>
            </div>
            <div class="form-group mt-4">
              <label for="content"><legend>Nội dung:</legend></label>
              <textarea cols="30" rows="10" class="form-control" id="content"></textarea>
            </div>
          </div>
          <div class="right form-group mt-4">
            <div class="template">
              <label for="title"><legend>Một số mẫu thông báo:</legend></label><br>
              <select class="template" id="template">
                <option value="empty"></option>
                <option value="study">Học tập</option>
                <option value="fee">Học phí</option>
                <option value="extracurricular">Ngoại khóa</option>
                <option value="social_payment">Chi trả xã hội</option>
                <option value="others">Thông báo khác</option>
              </select>
            </div>
            
            <div class="form-group mt-4">
              <label for="time-start" class="select_date">Ngày bắt đầu:</label>
              <input type="date" class="input-date" id="time-start" data-date="" data-date-format="DD/MM/YYYY">
              <button class="btn btn-primary time-start disable" name="reset_button">Bỏ chọn</button>
            </div>
            <div class="form-group mt-4">
              <label for="time-end" class="select_date">Ngày kết thúc:</label>
              <input type="date" class="input-date" id="time-end" data-date="" data-date-format="DD/MM/YYYY">
              <button class="btn btn-primary time-end disable" name="reset_button">Bỏ chọn</button>
            </div>
          </div>
        </div>';
    }

    function shield ()
    {
        $root_folder = $_ENV['LOCAL_ROOT_PROJECT'] ?? '';

        if (!isset($_SESSION['account_owner']) ||
            !isset($_SESSION['id_account'])) {

            header('Location: ' . $root_folder . '/ui/login/');
        }

        $now = time();

        if (isset($_SESSION['time_limit']) && $now > $_SESSION['time_limit']) {
            session_destroy();
            header('Location: ' . $root_folder . '/ui/login/');
        }

        $_SESSION['time_limit'] = $now + 3600;
    }
    $a = null;
    foreach ($a as $b)
    {
        echo 1;
    }