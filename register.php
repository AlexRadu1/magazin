<?php
include('includes/connect.php');
if (isset($_POST['submit'])) {
  $nume = mysqli_real_escape_string($con, $_POST['nume']);
  $prenume = mysqli_real_escape_string($con, $_POST['prenume']);
  $telefon = mysqli_real_escape_string($con, $_POST['telefon']);
  $adresa = mysqli_real_escape_string($con, $_POST['adresa']);
  $judet = mysqli_real_escape_string($con, $_POST['judet']);
  $name = mysqli_real_escape_string($con, $_POST['name']);
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $pass = mysqli_real_escape_string($con, md5($_POST['password']));
  $cpass = mysqli_real_escape_string($con, md5($_POST['cpassword']));
  $select_query = "SELECT * FROM utilizatori WHERE email='$email' OR `username`='$name' ";
  $select = mysqli_query($con, $select_query) or die('query failed');

  if (mysqli_num_rows($select) > 0) {
    $message[] = 'User already exist! ';
  } else {
    if ($pass != $cpass) {
      $message[] = 'Passwords do not  match! ';
    } else {
      mysqli_query($con, "INSERT INTO utilizatori (username,`password`,email,nume,prenume,telefon,adresa,cod_judet,tip) VALUES ('$name','$pass','$email','$nume','$prenume','$telefon','$adresa','$judet','0')") or die('query failed');
      $message[] = 'Registered successfully';
      header('location:login.php');
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="assets/fontawesome-free-6.4.0-web/css/all.css">
  <link rel="stylesheet" href="assets/fontawesome-free-6.4.0-web/css/all.min.css">
  <script src="assets/fontawesome-free-6.4.0-web/js/all.js"></script>
  <script src="assets/fontawesome-free-6.4.0-web/js/all.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <title>Register</title>
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
    <form action="" method="post">
      <h3>Register now</h3>
      <input type="text" name="nume" class="form-box" placeholder="Nume" required>
      <input type="text" name="prenume" class="form-box" placeholder="Prenume" required>
      <input type="tel" name="telefon" class="form-box" placeholder="Telefon" required>
      <input type="text" name="adresa" class="form-box" placeholder="Adresa" required>
      <select name="judet" class="form-box">
        <option value="">Judet</option>
        <?php
        $select_query = "SELECT * FROM judete";
        $result_query = mysqli_query($con, $select_query);
        while ($row = mysqli_fetch_assoc($result_query)) {
          $category_title = $row['denumire'];
          $category_id = $row['ID'];
          echo "<option value=$category_id>$category_title</option>";
        }
        ?>
      </select>
      <input type="text" name="name" required placeholder="enter username" class="form-box">
      <input type="email" name="email" required placeholder="enter email" class="form-box">
      <input type="password" name="password" required placeholder="enter password" class="form-box">
      <input type="password" name="cpassword" required placeholder="confirm password" class="form-box">
      <input type="submit" name="submit" class="btn" value="register now">
      <p>Already have an account? <a href="login.php"> Login now</a></p>
    </form>
  </div>
</body>

</html>