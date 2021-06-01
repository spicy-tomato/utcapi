<?php


    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/ui/shared/functions.php';
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
      <link rel="stylesheet" href="../../ui/forms/alert.css">
    ');
?>

<body>
<div class="container">

    <?php shared_navbar(); ?>

    <main>
        <form action="" autocomplete="off" enctype="multipart/form-data" onsubmit="return false">

            <div class=" mt-4">
                <legend>Tải tệp dữ liệu:</legend>

                <input id="fileUpload" type="file" name="fileUpload" multiple/>
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