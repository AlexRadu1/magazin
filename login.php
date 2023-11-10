<?php
include('includes/connect.php');
session_start();

if (isset($_POST['submit'])) {
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $pass = mysqli_real_escape_string($con, md5($_POST['password']));
  $select_query = "SELECT * FROM utilizatori WHERE email='$email' AND `password`='$pass' ";
  $select = mysqli_query($con, $select_query) or die('query failed');
  if (mysqli_num_rows($select) > 0) {
    $row = mysqli_fetch_assoc($select);
    $_SESSION['user_id'] = $row['ID'];
    $_SESSION['user_logged_in'] = true;
    if ($row['tip'] == 1) {
      if (isset($_SESSION['admin'])) {
        $_SESSION['admin'] = true;
      } else {
        $_SESSION['admin'] = true;
      }
    }
    if (isset($_SESSION['cart'])) {
      foreach ($_SESSION['cart'] as $key => $value) {
        $cart_query = mysqli_query($con, "SELECT * FROM cos WHERE user_id=" . $_SESSION['user_id'] . " AND cod_atribut_produs=" . $value['cod_atribut_produs'] . "");
        if (mysqli_num_rows($cart_query) > 0) {
          $row_cart = mysqli_fetch_assoc($cart_query);
          $id = $row_cart['ID'];
          $update_quant = $row_cart['quantity'] + $value['quantity'];
          $update_query = "UPDATE cos SET quantity='$update_quant' WHERE ID=$id";
          mysqli_query($con, $update_query);
        } else {
          $add_query = "INSERT INTO `cos` (user_id,cod_atribut_produs,quantity) VALUES ('" . $_SESSION['user_id'] . "','" . $value['cod_atribut_produs'] . "', '" . $value['quantity'] . "')";
          mysqli_query($con, $add_query);
        }
      }
    }
    header('location:index.php');
  } else $message[] = 'Incorrect email or password! ';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://kit.fontawesome.com/0ec3550c52.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <title>Login</title>
</head>

<body id="register">
  <?php
  if (isset($message)) {
    foreach ($message as $message) {
      echo "<div class='message' onclick='this.remove();'>" . $message . "</div>";
    }
  }
  ?>
  <div class="form-container">
    <a href="index.php" class="back-home">
      <img class="logo" src="./poze/logo.png" alt="shop-logo">
    </a>
    <form action="" method="post">
      <h3>Login now</h3>
      <input type="text" name="email" required placeholder="enter email" class="form-box">
      <input type="password" name="password" required placeholder="enter password" class="form-box">
      <input type="submit" name="submit" class="btn" value="Login now">
      <p>Don't have an account? <a href="register.php"> Register now</a></p>
    </form>
  </div>
  <script src="javascript.js"></script>
</body>

</html>