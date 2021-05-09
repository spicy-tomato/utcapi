<?php
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/ui/shared/functions.php';
    shield();
?>
<!doctype html>
<html lang="en">

<?php
    shared_header('Thông báo cho lớp học phần', '
      <!-- JQuery -->
      <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
      <!-- Select2 -->
      <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
      <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
      <!-- Select2 theme -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
      <!-- Custom select2 -->
      <script src="script/dist/js/select2.customSelectionAdapter.min.js"></script>
      <!-- AlertifyJS -->
      <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
      <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
      <!-- AlertifyJS Theme -->
      <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.rtl.min.css"/>
      <link rel="stylesheet" href="../alert.css">
    ');
?>

<body style="height: 100vh">
  <div class="container">

      <?php shared_navbar(); ?>

    <main>
      <form action="" autocomplete="off" onsubmit="return false">

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
          <button type="button"
                  class="btn btn-primary"
                  name="button"
                  id="submit_btn">Gửi
          </button>
        </div>
      </form>
    </main>
  </div>

  <script src="script/script.js" type="module"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
          crossorigin="anonymous"></script>
</body>
</html>
