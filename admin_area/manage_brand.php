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
    $nume = mysqli_real_escape_string($con, $_POST['nume']);
    $site = mysqli_real_escape_string($con, $_POST['site']);
    $check = mysqli_query($con, "SELECT * FROM branduri WHERE ID=$brand_info[ID]");
    if (mysqli_num_rows($check) > 0) {
      mysqli_query($con, "UPDATE branduri SET website='$site',denumire='$nume',website='$site' WHERE ID=$id");
      header("location:view_brands.php");
    }
  }
} else {
  if (isset($_POST['submit'])) {
    $nume = mysqli_real_escape_string($con, $_POST['nume']);
    $site = mysqli_real_escape_string($con, $_POST['site']);
    $check = mysqli_query($con, "SELECT * FROM branduri WHERE denumire='$nume'");
    mysqli_query($con, "INSERT INTO branduri (denumire,website) VALUES('$nume','$site')");
    header("location:view_brands.php");
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
      <div class="order-detail-header">Brand <?php echo (isset($_GET['id']) && $_GET['id'] != '') ? "#{$brand_info['ID']}" : "" ?> </div>
      <form method="post">
        <label for="brand_name">Nume brand</label>
        <input type="text" name="nume" id="brand_name" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? $brand_info['denumire'] : '' ?>">
        <label for="brand_site">Website</label>
        <input type="text" name="site" id="brand_site" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? $brand_info['website'] : '' ?>">
        <div class="submit-group">
          <input type="submit" name="submit" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? "Actualizeaza" : "Adauga" ?>" class="buttons">
        </div>
      </form>
    </div>
  </div>
  <script src="javascript.js" defer></script>
</body>

</html>