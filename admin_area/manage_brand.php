<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin']) {
  header("location:../index.php");
}

if (isset($_GET['id']) && $_GET['id'] != '') {
  $id = $_GET['id'];
  $q = mysqli_query($con, "SELECT * FROM branduri WHERE ID='$id'");
  $brand_info = mysqli_fetch_assoc($q);

  if (isset($_POST['submit'])) {
    $nume = $_POST['nume'];
    $check = mysqli_query($con, "SELECT * FROM branduri WHERE denumire='$nume'");
    if (mysqli_num_rows($check) > 0) {
      echo "<script>alert('Brandul este prezent deja!')</script>";
    } else {
      mysqli_query($con, "UPDATE branduri SET denumire='$nume' WHERE ID=$id");
      header("location:view_brands.php");
    }
  }
} else {
  if (isset($_POST['submit'])) {
    $nume = $_POST['nume'];
    $check = mysqli_query($con, "SELECT * FROM branduri WHERE denumire='$nume'");
    if (mysqli_num_rows($check) > 0) {
      echo "<script>alert('Brandul este prezent deja!')</script>";
    } else {
      mysqli_query($con, "INSERT INTO branduri (denumire) VALUES('$nume')");
      header("location:view_brands.php");
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
  <title>Document</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>

<body>
  <?php include("includes/header.php") ?>
  <div class="wrapper">
    <?php include("includes/sidebar.php") ?>
    <div class="container">
      <div class="order-detail-header">Brand <?php echo (isset($_GET['id']) && $_GET['id'] != '') ? "#{$brand_info['ID']}" : "" ?> </div>
      <form method="post">
        <label for="brand_name">Nume brand</label>
        <input type="text" name="nume" id="brand_name" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? $brand_info['denumire'] : '' ?>">
        <div class="submit-group">
          <input type="submit" name="submit" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? "Actualizeaza" : "Adauga" ?>" class="buttons">
        </div>
      </form>
    </div>
  </div>
</body>

</html>