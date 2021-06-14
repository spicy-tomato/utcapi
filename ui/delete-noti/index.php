<?php
    session_start();
    include_once dirname(__DIR__, 2) . '/ui/shared/functions.php';
    shield();
?>
<!doctype html>
<html lang="en">

<?php
    shared_header('Xóa thông báo', '
      <!-- JQuery -->
      <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
      <!-- AlertifyJS -->
      <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
      <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
      <!-- AlertifyJS Theme -->
      <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.rtl.min.css"/>
      <link rel="stylesheet" href="../css/alert.css">
      <link rel="stylesheet" href="css/style.css">
      <link rel="stylesheet" href="css/custom-confirm.css">
    ');
?>

<body>
<div class="container">

    <?php shared_navbar(); ?>

    <main id="main-area">
      <div id="left-area">
        <p>Lịch sử những thông báo đã được gửi sẽ được hiển thị toàn bộ dưới đây:</p>
        <table id="noti-select">
          <tr>
            <th></th>
            <th class="title">Tiêu đề</th>
            <th class="content">Nội dung</th>
            <th class="time-create">Thời gian gửi</th>
          </tr>
        </table>
      </div>
      <div id="right-area">
        <button type="button"
                class="btn btn-primary"
                name="button"
                id="submit_btn">Xóa
        </button>
      </div>
    </main>
</div>
<script src="script/script.js" type="module"></script>
<script src="script/custom-confirm.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
        crossorigin="anonymous"></script>
</body>