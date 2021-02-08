<!doctype html>
<html lang="en">

<?php
    session_start();

    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/config/db.php";
    include_once '../shared/functions.php';

    if (isset($_SESSION['department_name'])){
      header('Location: ../home');
    }

    if (isset($_POST['btn-submit']) && isset($_POST['username']) && isset($_POST['password'])) {
        $db   = new Database();
        $conn = $db->connect();
    }

    shared_header("Đăng nhập");
?>

<body class="text-center cc_cursor bg-light">
  <form method="post" action="login.php" class="form-signin cc_cursor border border-dark mb-4 rounded">
    <div class="mt-3 mb-3">
      <div class="input-group">
        <span class="col-lg-1 justify-content-center align-items-center d-flex">
          <i class="fas fa-user d-block"></i>
        </span>
        <label>
          <input type="text"
                 name="username"
                 class="form-control cc_cursor rounded"
                 placeholder="Tên đăng nhập">
        </label>
      </div>
    </div>

    <div class="mb-3">
      <div class="input-group">
        <span class="col-lg-1 justify-content-center align-items-center d-flex">
          <i class="fas fa-key"></i>
        </span>
        <label>
          <input type="password"
                 name="password"
                 class="form-control cc_cursor rounded"
                 placeholder="Mật khẩu">
        </label>
      </div>
    </div>
    <button class="mb-3 btn btn-primary" type="submit" name="btn-submit">Đăng nhập</button>
      <?php
          if (isset($_GET['login-failed'])) {
              ?>
              <p class="text-danger">Đăng nhập thất bại</p>
              <?php
          }
      ?>
  </form>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
          crossorigin="anonymous"></script>
</body>
</html>
