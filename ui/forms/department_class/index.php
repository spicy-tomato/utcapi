<?php
session_start();
include_once '../../shared/functions.php';
shield();
shared_header('Gửi thông báo theo lớp');
?>
<body>
<div>
    <form action="temp.php" method="POST" onsubmit="return false">
        <div class="academic_year">
            <label>Khóa: </label>
            <input type="checkbox" class="academic_year" value="all" id="all_academic_year" onclick="tickAll(this)">
            <label for="all_academic_year">Chọn tất</label>
            <input type="checkbox" class="academic_year" name="academic_year" value="K60" id="K60">
            <label for="K60">K60</label>
            <input type="checkbox" class="academic_year" name="academic_year" value="K59" id="K59">
            <label for="K59">K59</label>
            <input type="checkbox" class="academic_year" name="academic_year" value="K58" id="K58">
            <label for="K58">K58</label>
            <input type="checkbox" class="academic_year" name="academic_year" value="K57" id="K57">
            <label for="K57">K57</label>
            <input type="checkbox" class="academic_year" name="academic_year" value="K56" id="K56">
            <label for="K56">K56</label>
            <input type="checkbox" class="academic_year" name="academic_year" value="K55" id="K55">
            <label for="K55">K55</label>
            <input type="checkbox" class="academic_year" name="academic_year" value="K54" id="K54">
            <label for="K54">K54</label>
        </div>
        <br>

        <div class="faculty">
            <label>Khoa: </label>
            <input type="checkbox" class="faculty" id="all_faculty" value="all" onclick="tickAll(this)">
            <label for="all_faculty">Chọn tất</label>
            <input type="checkbox" class="faculty" name="faculty" value="CK" id="CK">
            <label for="CK">Cơ khí</label>
            <input type="checkbox" class="faculty" name="faculty" value="CNTT" id="CNTT">
            <label for="CNTT">Công nghệ thông tin</label>
            <input type="checkbox" class="faculty" name="faculty" value="CT" id="CT">
            <label for="CT">Công trình</label>
            <input type="checkbox" class="faculty" name="faculty" value="DDT" id="DDT">
            <label for="DDT">Điện điện tử</label>
            <input type="checkbox" class="faculty" name="faculty" value="GDQP" id="GDQP">
            <label for="GDQP">Giáo dục quốc phòng</label>
            <input type="checkbox" class="faculty" name="faculty" value="GDTC" id="GDTC">
            <label for="GDTC">Giáo dục thể chất</label>
            <input type="checkbox" class="faculty" name="faculty" value="KHCB" id="KHCB">
            <label for="KHCB">Khoa học cơ bản</label>
            <input type="checkbox" class="faculty" name="faculty" value="KTXD" id="KTXD">
            <label for="KTXD">Kỹ thuật xây dựng</label>
            <input type="checkbox" class="faculty" name="faculty" value="LLCT" id="LLCT">
            <label for="LLCT">Lý luận chính trị</label>
            <input type="checkbox" class="faculty" name="faculty" value="VTKT" id="VTKT">
            <label for="VTKT">Vận tải kinh tế</label>
        </div>

        <div class="class"></div>

        <button name="button">Gửi</button>
    </form>

</div>
<script src="script/index.js"></script>
</body>
</html>
