<!doctype html>
<html lang="en">

<?php
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . "/utcapi/ui/shared/functions.php";

    shield();
    shared_header('Thông báo cho sinh viên');
?>
<body>
  <div class="container">

    <?php shared_navbar(); ?>

    <main>
      <form action="" autocomplete="off" enctype="multipart/form-data">

          <?php shared_form(); ?>

        <div class=" mt-4">
          <legend>Tải tệp danh sách sinh viên nhân thông báo:</legend>

          <input type="file" name="fileUpload"/>
          <input type="submit" name="uploadclick" value="Upload"/>

        <div>
          <button type="button"
                  class="btn btn-primary"
                  name="button"
                  id="submit_btn">Gửi
          </button>
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
