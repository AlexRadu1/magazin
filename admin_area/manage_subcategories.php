<?php
include("../includes/connect.php");
if (isset($_GET['id']) && $_GET['id'] != '') {
  $id = $_GET['id'];
  $q = mysqli_query($con, "SELECT * FROM subcategorii WHERE ID='$id'");
  $subcat_info = mysqli_fetch_assoc($q);

  if (isset($_POST['submit'])) {
    $nume = $_POST['nume'];
    $cat_id = $_POST['category'];
    $check = mysqli_query($con, "SELECT * FROM subcategorii WHERE denumire='$nume' AND cod_categorie=$cat_id");
    if (mysqli_num_rows($check) > 0) {
      echo "<script>alert('Categoria este prezent deja!')</script>";
    } else {
      mysqli_query($con, "UPDATE subcategorii SET denumire='$nume',cod_categorie=$cat_id WHERE ID=$id");
      header("location:view_subcategories.php");
    }
  }
} else {
  if (isset($_POST['submit'])) {
    $nume = $_POST['nume'];
    $cat_id = $_POST['category'];
    $check = mysqli_query($con, "SELECT * FROM subcategorii WHERE denumire='$nume' AND cod_categorie=$cat_id");
    if (mysqli_num_rows($check) > 0) {
      echo "<script>alert('Categoria este prezenta deja!')</script>";
    } else {
      mysqli_query($con, "INSERT INTO subcategorii (denumire,cod_categorie) VALUES('$nume','$cat_id')");
      header("location:view_subcategories.php");
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
      <div class="order-detail-header">Subcategorie <?php echo (isset($_GET['id']) && $_GET['id'] != '') ? "#{$subcat_info['ID']}" : "" ?></div>
      <form method="post">
        <label for="category">Categorie</label>
        <select name="category" id="category">
          <?php
          $sq = mysqli_query($con, "SELECT * FROM categorii");
          while ($rsq = mysqli_fetch_assoc($sq)) {
          ?>
            <option value="<?= $rsq['ID'] ?>" <?php if (isset($_GET['id']) && $subcat_info['ID'] == $rsq['ID']) echo "selected" ?>><?= $rsq['denumire'] ?></option>
          <?php
          }

          ?>
        </select>
        <label for="cat_name">Nume subcategorie: </label>
        <input type="text" name="nume" id="cat_name" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? $subcat_info['denumire'] : '' ?>">
        <div class="submit-group">
          <input type="submit" name="submit" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? "Actualizeaza" : "Adauga" ?>" class="buttons">
        </div>
      </form>
    </div>
  </div>
</body>

</html>