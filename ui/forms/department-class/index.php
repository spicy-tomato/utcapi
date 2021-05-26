<?php
    session_start();
    include_once dirname(__DIR__, 3) . '/ui/shared/functions.php';
    shield();
?>
<!doctype html>
<html lang="en">

<?php

    shared_header('Thông báo cho khoá - khoa - lớp', '
      <!-- JQuery -->
      <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
      <!-- AlertifyJS -->
      <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
      <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
      <!-- AlertifyJS Theme -->
      <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.rtl.min.css"/>
      <!-- custom input date -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
      <link rel="stylesheet" href="../alert.css">
    ');
?>

<body>
  <div class="container">

      <?php shared_navbar(); ?>

    <main>
      <form action="" autocomplete="off" onsubmit="return false">

          <?php shared_form(); ?>

        <div class="form-group mt-4" id="academic_year_area">
          <legend>Khoá:</legend>
        </div>
        <br>

        <div class="form-group mt-4" id="faculty_area">
          <legend>Khoa:</legend>
          <div class="form-check form-check-inline">
            <input type="checkbox"
                   class="faculty form-check-input academic-year-faculty"
                   id="all_faculty"
                   value="all">
            <label for="all_faculty" class="form-check-label">Chọn tất cả</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="faculty form-check-input academic-year-faculty" name="faculty" value="CK" id="CK">
            <label for="CK" class="form-check-label">Cơ khí</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="faculty form-check-input academic-year-faculty" name="faculty" value="CNTT" id="CNTT">
            <label for="CNTT" class="form-check-label">Công nghệ thông tin</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="faculty form-check-input academic-year-faculty" name="faculty" value="CT" id="CT">
            <label for="CT" class="form-check-label">Công trình</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="faculty form-check-input academic-year-faculty" name="faculty" value="DDT" id="DDT">
            <label for="DDT" class="form-check-label">Điện điện tử</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="faculty form-check-input academic-year-faculty" name="faculty" value="KTXD" id="KTXD">
            <label for="KTXD" class="form-check-label">Kỹ thuật xây dựng</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="faculty form-check-input academic-year-faculty" name="faculty" value="VTKT" id="VTKT">
            <label for="VTKT" class="form-check-label">Vận tải kinh tế</label>
          </div>
        </div>

        <div class="class"></div>

        <button type="button"
                class="btn btn-primary"
                name="button"
                id="submit_btn">Gửi
        </button>
      </form>

    </main>

  </div>
  <script src="script/script.js" type="module"></script>
  <script src="../shared.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
          crossorigin="anonymous"></script>
</body>
</html>
