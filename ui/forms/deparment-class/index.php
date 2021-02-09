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

	<?php
	shared_navbar(); ?>

  <main>
    <form onsubmit="return false">

		<?php
		shared_form(); ?>

      <div class="form-group mt-4">
        <legend>Khoá:</legend>
        <div class="form-check form-check-inline">
          <input type="checkbox"
                 class="academic_year form-check-input"
                 id="all_academic_year"
                 value="all"
                 onclick="tickAll(this)">
          <label for="all_academic_year" class="form-check-label">Chọn tất cả</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" class="academic_year form-check-input" id="K54" name="academic_year" value="K54">
          <label for="K54" class="form-check-label">K54</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" class="academic_year form-check-input" id="K55" name="academic_year" value="K55">
          <label for="K55" class="form-check-label">K55</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" class="academic_year form-check-input" id="K56" name="academic_year" value="K56">
          <label for="K56" class="form-check-label">K56</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" class="academic_year form-check-input" id="K57" name="academic_year" value="K57">
          <label for="K57" class="form-check-label">K57</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" class="academic_year form-check-input" id="K58" name="academic_year" value="K58">
          <label for="K58" class="form-check-label">K58</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" class="academic_year form-check-input" id="K59" name="academic_year" value="K59">
          <label for="K59" class="form-check-label">K59</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" class="academic_year form-check-input" id="K60" name="academic_year" value="K60">
          <label for="K60" class="form-check-label">K60</label>
        </div>
      </div>
      <br>

      <div class="form-group">
        <legend>Khoa:</legend>
        <div class="form-check form-check-inline">
          <input type="checkbox"
                 class="faculty form-check-input"
                 id="all_faculty"
                 value="all"
                 onclick="tickAll(this)">
          <label for="all_faculty" class="form-check-label">Chọn tất cả</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" class="faculty form-check-input" id="CK" name="faculty" value="CK">
          <label for="CK" class="form-check-label">Cơ khí</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" class="faculty form-check-input" id="CNTT" name="faculty" value="CNTT">
          <label for="CNTT" class="form-check-label">Công nghệ thông tin</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" class="faculty form-check-input" id="CT" name="faculty" value="CT">
          <label for="CT" class="form-check-label">Công trình</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" class="faculty form-check-input" id="DDT" name="faculty" value="DDT">
          <label for="DDT" class="form-check-label">Điện điện tử</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" class="faculty form-check-input" id="GDQP" name="faculty" value="GDQP">
          <label for="GDQP" class="form-check-label">Giáo dục quốc phòng</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" class="faculty form-check-input" id="GDTC" name="faculty" value="GDTC">
          <label for="GDTC" class="form-check-label">Giáo dục thể chất</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" class="faculty form-check-input" id="KHCB" name="faculty" value="KHCB">
          <label for="KHCB" class="form-check-label">Khoa học cơ bản</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" class="faculty form-check-input" id="KTXD" name="faculty" value="KTXD">
          <label for="KTXD" class="form-check-label">Kỹ thuật xây dựng</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" class="faculty form-check-input" id="LLCT" name="faculty" value="LLCT">
          <label for="LLCT" class="form-check-label">Lý luận chính trị</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" class="faculty form-check-input" id="VTKT" name="faculty" value="VTKT">
          <label for="VTKT" class="form-check-label">Vận tải kinh tế</label>
        </div>
      </div>

      <div class="class"></div>

      <button type="submit" class="btn btn-primary" name="button" >Gửi</button>
    </form>

  </main>

</div>
<script src="script/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
        crossorigin="anonymous"></script>
</body>
</html>
