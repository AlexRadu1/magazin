<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin']) {
  header("location:../index.php");
}

if (isset($_GET['id']) && $_GET['id'] != '') {
  $id = $_GET['id'];
  $q = mysqli_query($con, "SELECT * FROM culori WHERE ID='$id'");
  $color_info = mysqli_fetch_assoc($q);

  if (isset($_POST['submit'])) {
    $nume = $_POST['nume'];
    $check = mysqli_query($con, "SELECT * FROM culori WHERE denumire='$nume'");
    if (mysqli_num_rows($check) > 0) {
      echo "<script>alert('$nume deja exista!')</script>";
    } else {
      mysqli_query($con, "UPDATE culori SET denumire='$nume' WHERE ID=$id");
      header("location:view_colors.php");
    }
  }
} else {
  if (isset($_POST['submit'])) {
    $nume = $_POST['nume'];
    $check = mysqli_query($con, "SELECT * FROM culori WHERE denumire='$nume'");
    if (mysqli_num_rows($check) > 0) {
      echo "<script>alert('$nume deja exista!')</script>";
    } else {
      mysqli_query($con, "INSERT INTO culori (denumire) VALUES('$nume')");
      header("location:view_colors.php");
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
      <div class="order-detail-header">Culoare <?php echo (isset($_GET['id']) && $_GET['id'] != '') ? "#{$color_info['ID']}" : "" ?> </div>
      <form method="post">
        <label for="cat_name">Nume culoare: </label>
        <input type="text" name="nume" id="cat_name" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? $color_info['denumire'] : '' ?>">
        <div class="submit-group">
          <input type="submit" name="submit" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? "Actualizeaza" : "Adauga" ?>" class="buttons">
        </div>
      </form>
    </div>
  </div>
</body>

</html>