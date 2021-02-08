<!doctype html>
<html lang="en">

<?php
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/ui/shared/functions.php";

    shield();
    shared_header('Thông báo cho lớp học phần', '
      <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
      <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
      <script src="script/dist/js/select2.customSelectionAdapter.min.js"></script>
      <link href="script/dist/css/select2.customSelectionAdapter.css" rel="stylesheet" />
    ');
?>

<body style="height: 100vh">
  <div class="container">

      <?php shared_navbar(); ?>

    <main>
      <form action="" autocomplete="off">

          <?php shared_form(); ?>

        <div class="row mt-4">
          <div class="col-5 col-lg-4">
            <div class="form-group row">
              <label for="module-class-id">
                <legend>Mã học phần:</legend>
              </label>
              <div class="col-12 col-lg-8">
                <select name="" id="module-class-id" class="form-control" multiple="multiple"></select>
              </div>
            </div>
          </div>
          <div class="col-7 col-lg-8">
            <div class="form-group row">
              <legend>Các lớp học phần đã chọn:</legend>
            </div>
            <div id="list"></div>
          </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
          <button name="button" type="submit" class="btn btn-primary">Gửi</button>
        </div>
      </form>
    </main>
  </div>

  <script src="script/script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
          crossorigin="anonymous"></script>
</body>
</html>
