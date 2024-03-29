<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin']) {
  header("location:../index.php");
}

if (isset($_GET['id']) && $_GET['id'] != '') {
  $id = $_GET['id'];
  $q = mysqli_query($con, "SELECT * FROM marimi WHERE ID='$id'");
  $size_info = mysqli_fetch_assoc($q);

  if (isset($_POST['submit'])) {
    $nume = $_POST['nume'];
    $check = mysqli_query($con, "SELECT * FROM marimi WHERE denumire='$nume'");
    if (mysqli_num_rows($check) > 0) {
      echo "<script>alert('$nume deja exista!')</script>";
    } else {
      mysqli_query($con, "UPDATE marimi SET denumire='$nume' WHERE ID=$id");
      header("location:view_sizes.php");
    }
  }
} else {
  if (isset($_POST['submit'])) {
    $nume = $_POST['nume'];
    $check = mysqli_query($con, "SELECT * FROM marimi WHERE denumire='$nume'");
    if (mysqli_num_rows($check) > 0) {
      echo "<script>alert('$nume deja exista!')</script>";
    } else {
      mysqli_query($con, "INSERT INTO marimi (denumire) VALUES('$nume')");
      header("location:view_sizes.php");
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
  <script src="https://kit.fontawesome.com/0ec3550c52.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>

<body>
  <?php include("includes/header.php") ?>
  <div class="wrapper">
    <?php include("includes/sidebar.php") ?>
    <div class="container">
      <div class="order-detail-header">Marime <?php echo (isset($_GET['id']) && $_GET['id'] != '') ? "#{$size_info['ID']}" : "" ?> </div>
      <form method="post">
        <label for="cat_name">Nume marime: </label>
        <input type="text" name="nume" id="cat_name" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? $size_info['denumire'] : '' ?>">
        <div class="submit-group">
          <input type="submit" name="submit" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? "Actualizeaza" : "Adauga" ?>" class="buttons">
        </div>
      </form>
    </div>
  </div>
  <script src="javascript.js" defer></script>
</body>

</html>