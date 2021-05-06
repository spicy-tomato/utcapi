<?php
    header("Location: /ui/login/");
    echo 111;
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/ui/shared/functions.php";
    shield();
?>
<!doctype html>
<html lang="en">

<?php
    shared_header('Trang chủ');
?>
<body>
    <?php shared_navbar(); ?>

  <main>

  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
          crossorigin="anonymous"></script>
</body>
</html>
