<?php
    session_start();
    include_once dirname(__DIR__, 2) . '/ui/shared/functions.php';
    shield();
?>
<!doctype html>
<html lang="en">

<?php
    shared_header('Trang chủ');
?>
<body>
<?php
    shared_navbar(); ?>

<main>
  <div id="intro-container">
    <svg xmlns="http://www.w3.org/2000/svg">

      <!-- filterUnits is required to prevent clipping the blur outside the viewBox -->

      <filter id="motion-blur-filter" filterUnits="userSpaceOnUse">

        <!-- We only want horizontal blurring. x: 100, y: 0 -->

        <feGaussianBlur stdDeviation="100 0"></feGaussianBlur>
      </filter>

    <span filter-content="U" class="intro">UTC</span><br>
    <span filter-content="K" class="intro">Kết nối</span>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
        crossorigin="anonymous"></script>
</body>
</html>
