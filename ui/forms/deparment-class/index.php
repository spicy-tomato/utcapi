<!doctype html>
<html lang="en">
<?php
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/ui/shared/functions.php";

    shield();
    shared_header('Thông báo cho khoá - khoa - lớp');
?>
<body>
  <div class="container">

      <?php shared_navbar();
      ?>
    <div>
      <form action="temp.php" method="POST" onsubmit="return false">
        <div class="form-group">
          <label for="title">Tiêu đề</label>
          <input type="text" class="form-control" id="title">
        </div>
        <div class="form-group">
          <label for="content">Nội dung</label>
          <textarea id="content" cols="30" rows="10" class="form-control"></textarea>
        </div>

        <div class="form-group">
          <legend>Khoá:</legend>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="academic_year form-check-input" value="all" id="all_academic_year"
                   onclick="tickAll(this)">
            <label for="all_academic_year" class="form-check-label">Chọn tất cả</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="academic_year form-check-input" name="academic_year" value="K60" id="K60">
            <label for="K60" class="form-check-label">K60</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="academic_year form-check-input" name="academic_year" value="K59" id="K59">
            <label for="K59" class="form-check-label">K59</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="academic_year form-check-input" name="academic_year" value="K58" id="K58">
            <label for="K58" class="form-check-label">K58</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="academic_year form-check-input" name="academic_year" value="K57" id="K57">
            <label for="K57" class="form-check-label">K57</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="academic_year form-check-input" name="academic_year" value="K56" id="K56">
            <label for="K56" class="form-check-label">K56</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="academic_year form-check-input" name="academic_year" value="K55" id="K55">
            <label for="K55" class="form-check-label">K55</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="academic_year form-check-input" name="academic_year" value="K54" id="K54">
            <label for="K54" class="form-check-label">K54</label>
          </div>
        </div>
        <br>

        <div class="form-group">
          <legend>Khoa:</legend>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="faculty form-check-input" id="all_faculty" value="all" onclick="tickAll(this)">
            <label for="all_faculty form-check-label">Chọn tất cả</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="faculty form-check-input" name="faculty" value="CK" id="CK">
            <label for="CK form-check-label">Cơ khí</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="faculty form-check-input" name="faculty" value="CNTT" id="CNTT">
            <label for="CNTT form-check-label">Công nghệ thông tin</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="faculty form-check-input" name="faculty" value="CT" id="CT">
            <label for="CT form-check-label">Công trình</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="faculty form-check-input" name="faculty" value="DDT" id="DDT">
            <label for="DDT form-check-label">Điện điện tử</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="faculty form-check-input" name="faculty" value="GDQP" id="GDQP">
            <label for="GDQP form-check-label">Giáo dục quốc phòng</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="faculty form-check-input" name="faculty" value="GDTC" id="GDTC">
            <label for="GDTC form-check-label">Giáo dục thể chất</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="faculty form-check-input" name="faculty" value="KHCB" id="KHCB">
            <label for="KHCB form-check-label">Khoa học cơ bản</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="faculty form-check-input" name="faculty" value="KTXD" id="KTXD">
            <label for="KTXD form-check-label">Kỹ thuật xây dựng</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="faculty form-check-input" name="faculty" value="LLCT" id="LLCT">
            <label for="LLCT form-check-label">Lý luận chính trị</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="checkbox" class="faculty form-check-input" name="faculty" value="VTKT" id="VTKT">
            <label for="VTKT form-check-label">Vận tải kinh tế</label>
          </div>
        </div>

        <div class="class"></div>

        <button name="button">Gửi</button>
      </form>

    </div>
  </div>
  <script src="script/index.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
          crossorigin="anonymous"></script>
</body>
</html>
