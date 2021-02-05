<!doctype html>
<html lang="en">

<?php
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/ui/shared/functions.php";
    shield();
    shared_header('Thông báo cho lớp học phần');

?>
<body>
  <div class="container">

      <?php shared_navbar(); ?>

    <main>
      <form action="temp.php">

          <?php shared_form(); ?>

        <div class="row">
          <div class="col-3">
            <div class="form-group">
              <label>Học học phần: </label>
              <select id="moduleClass" class="form-control">

              </select>
            </div>
          </div>
          <div class="col-9">

          </div>
        </div>
      </form>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
          crossorigin="anonymous"></script>
</body>
</html>
