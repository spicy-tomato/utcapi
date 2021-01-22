<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login</title>
</head>
<body style="height: 100vh;">
<div style="display: flex; justify-content: center; align-items: center;">
  <form method="POST" action="login.php" style="width: 50%">
    <fieldset>
      <legend>Đăng nhập</legend>
      <table>
        <tr>
          <td>Mã sinh viên</td>
          <td><input type="text" name="id" size="30"></td>
        </tr>
        <tr>
          <td>Mật khẩu</td>
          <td><input type="password" name="password" size="30"></td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input type="submit" name="btn-submit" value="Đăng nhập">
          </td>
        </tr>
            <?php
                if (isset($_GET['login-failed'])) {
                    ?><tr style="color: #ff0000"></tr><?php
                }
            ?>
      </table>
    </fieldset>
  </form>
</div>
</body>
</html>
