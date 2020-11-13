<?php
session_start();
if (isset($_GET['logout'])) {
  session_destroy();
  header('Location: login.php');
  exit();
}

if (isset($_SESSION['username'])) {
  header('Location: index.php');
}
require '../db/koneksi.php';
if (isset($_POST['signin'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $pass = md5($pass);

    /* Start Patch Bypass SQLi, remove this line to patch

    $stmt_login = mysqli_prepare($conn, "SELECT * from `m_user` WHERE `username`= ? and `password`= ?");
    mysqli_stmt_bind_param($stmt_login, "ss", $user, $pass);
    mysqli_stmt_execute($stmt_login);
    $count = mysqli_stmt_fetch($stmt_login);

    //don't forget to comment vulnerable Bypass SQLi section

    End Patch Bypass SQLi, remove this line to patch */

    // Start Vulnerable Bypass SQLi 
    // /*
    $sql = "SELECT * from `m_user` WHERE `username`= '$user' and `password`= '$pass'";

    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $count = mysqli_num_rows($result);
    // */
    // End Vulnerable Bypass SQLi

    if ($count >= 1) {
      $_SESSION['username'] = $user;
      header('Location: index.php');
    } else {
      echo "<script>alert('login error mas')</script>";
    }
}

if (isset($_POST['signup'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $pass = md5($pass);
    

    $sql = "SELECT * from `m_user` WHERE `username`= '$user' and `password`= '$pass'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $count = mysqli_num_rows($result);

    if ($count < 1) {


      $sql = "INSERT INTO `m_user` (`id`, `username`, `password`) VALUES (NULL, '$user', '$pass')";
      $insert = mysqli_query($conn, $sql) or die(mysqli_error($conn));
      
      if ($insert) {
          echo "<script>alert('user successfully added')</script>";
      }
    } else {
      echo "<script>alert('error registration')</script>";
    }
}

require 'header.php';
?>
    <section class="portfolio" id="portfolio">
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
          <form action="login.php" method="POST">
          <div class="form-group">
            <h3 align="center">SIGN-IN</h3>
            <label for="user">Username:</label>
            <input type="text" class="form-control" id="username" placeholder="username" name="username">
          </div>
          <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
          </div>
          <input type="submit" value="signin" name="signin" />
          </form>
          </div>
          <div class="col-sm-6">
          <form action="login.php" method="POST">
          <div class="form-group">
            <h3 align="center">SIGN-UP</h3>
            <label for="user">Username:</label>
            <input type="text" class="form-control" id="username" placeholder="username" name="username">
          </div>
          <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
          </div>
          <input type="submit" value="signup" name="signup" />
          </form>
          </div>
        </div>
      </div>
    </section>

<?php
require 'footer.php';
?>