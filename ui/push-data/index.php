<?php
    session_start();
    include_once dirname(__DIR__, 2) . '/ui/shared/functions.php';
    shield();
?>
<!doctype html>
<html lang="en">

<?php
    shared_header('Nhập dữ liệu', '
      <!-- JQuery -->
      <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
      <!-- AlertifyJS -->
      <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
      <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
      <!-- AlertifyJS Theme -->
      <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.rtl.min.css"/>
      <link rel="stylesheet" href="../css/alert.css">
      <link rel="stylesheet" href="../css/style.css">
    ');
?>

<body>
<div class="container">

    <?php
        shared_navbar(); ?>

  <main>
    <form action="" autocomplete="off" enctype="multipart/form-data" onsubmit="return false">

      <div class=" mt-4">
        <span>Tải lên file điểm danh để phục vụ công việc nhập dữ liệu bảng lớp học, sinh viên và bảng tham gia vào cơ sở dữ liệu</span><br>
        <a href="src/data.xls">Tải file mẫu tại đây</a><br>
        <span
            id="notice">*Lưu ý: Tính năng này đang được phát triển nên được khuyến khích chỉ nên tải 1 file một lần</span><br><br>
        <legend>Tải lên tệp dữ liệu ở đây:</legend>
        <label for="fileUpload" class="custom-file-upload">
          <i class="fa fa-cloud-upload"></i> Tải tệp
        </label>
        <input id="fileUpload" type="file" name="fileUpload" accept="application/vnd.ms-excel" style="display:none;"
               multiple/>
        <button type="button"
                class="btn btn-primary"
                name="button"
                id="submit_btn">Gửi
        </button>
        <p>Các tệp đã được chọn:</p>
        <div id="file-list"></div><br>
        <p>File cùng tên chứa thông tin chi tiết ngoại lệ xảy ra trong quá trình import:</p>
        <div id="file-exception"></div>
      </div>

    </form>
  </main>
</div>
<script src="script/script.js" type="module"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
        crossorigin="anonymous"></script>
</body>