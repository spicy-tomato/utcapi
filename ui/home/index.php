<?php


    session_start();
    include_once '../shared/functions.php';
    shield();
    shared_header('Trang chủ');
?>
<body>
  <header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark px-5">
      <a href="../home/" class="navbar-brand">Trang chủ</a>
      <div class="collapse navbar-collapse d-flex justify-content-between">
        <ul class="navbar-nav">
          <li class="navbar-item">
            <a href="../forms/department_class/" class="nav-link">Form 1</a>
          </li>
          <li class="navbar-item">
            <a href="" class="nav-link">Form 2</a>
          </li>
          <li class="navbar-item">
            <a href="" class="nav-link">Form 3</a>
          </li>
        </ul>

        <div class="dropdown">
          <button type="button"
                  class="btn btn-secondary dropdown-toggle"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                  id="dropdownMenuButton">
            <?php echo $_SESSION['department_name']?>
            <i class="fas fa-cogs"></i>
          </button>
          <ul class="dropdown-menu mr-5" aria-labelledby="dropdownMenuButton">
            <li><a href="" class="dropdown-item">Cài đặt</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a href="logout.php" class="dropdown-item">Đăng xuất</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <main>

  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
          crossorigin="anonymous"></script>
</body>
</html>
