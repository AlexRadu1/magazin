<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin']) {
  header("location:../index.php");
}

if (isset($_GET['id']) && $_GET['id'] != '') {
  $id = $_GET['id'];
  $q = mysqli_query($con, "SELECT * FROM furnizori WHERE ID='$id'");
  $furnizor_info = mysqli_fetch_assoc($q);

  if (isset($_POST['submit'])) {
    $name_field = $_POST['nume_furnizor'];
    $address_field = $_POST['adresa_furnizor'];
    $judet_field = $_POST['judet_field'];
    $iban_field = $_POST['iban_field'];
    $banca_field = $_POST['banca_field'];
    $cif_input = $_POST['cif_input'];
    $check = mysqli_query($con, "SELECT * FROM furnizori WHERE ID=$id");
    mysqli_query($con, "UPDATE `furnizori` SET denumire='$name_field',adresa='$address_field',cod_judet='$judet_field ',IBAN='$iban_field',Banca='$banca_field',CIF='$cif_input' WHERE ID=$id");
    header("location:view_providers.php");
  }
} else {
  if (isset($_POST['submit'])) {
    $name_field = $_POST['nume_furnizor'];
    $address_field = $_POST['adresa_furnizor'];
    $judet_field = $_POST['judet_field'];
    $iban_field = $_POST['iban_field'];
    $banca_field = $_POST['banca_field'];
    $cif_input = $_POST['cif_input'];
    $check = mysqli_query($con, "SELECT * FROM furnizori WHERE denumire='$name_field'");
    if (mysqli_num_rows($check) > 0) {
      echo "<script>alert('Nume furnizor exista deja!')</script>";
    } else {
      mysqli_query($con, "INSERT INTO `furnizori` (denumire,adresa,cod_judet,IBAN,Banca,CIF)
      VALUES (' $name_field','$address_field','$judet_field','$iban_field','$banca_field','$cif_input')");
      header("location:view_providers.php");
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
      <div class="order-detail-header">Furnizor <?php echo (isset($_GET['id']) && $_GET['id'] != '') ? "#{$furnizor_info['ID']}" : "" ?></div>
      <form method="post">
        <label for="name_field" class="">Nume Furnizor</label>
        <input type="text" name="nume_furnizor" id="name_field" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? $furnizor_info['denumire'] : '' ?>" placeholder="Adauga nume furnizor" autocomplete="off" required="required">

        <label for="address_field" class="">Adresa</label>
        <input type="text" name="adresa_furnizor" id="address_field" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? $furnizor_info['adresa'] : '' ?>" placeholder="Adauga adresa furnizor" autocomplete="off" required="required">

        <label for="judete">Judet</label>
        <select name="judet_field" id="judete">
          <?php
          $sq = mysqli_query($con, "SELECT * FROM judete");
          while ($rsq = mysqli_fetch_assoc($sq)) {
          ?>
            <option value="<?= $rsq['ID'] ?>" <?php if (isset($_GET['id']) && $furnizor_info['cod_judet'] == $rsq['ID']) echo "selected" ?>><?= $rsq['denumire'] ?></option>
          <?php
          }
          ?>
        </select>

        <label for="iban_input">IBAN</label>
        <input type="text" name="iban_field" id="iban_input" placeholder="IBAN..." autocomplete="off" required="required" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? $furnizor_info['IBAN'] : '' ?>">

        <label for="banca_input">Banca</label>
        <input type="text" name="banca_field" id="banca_input" placeholder="Nume banca" autocomplete="off" required="required" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? $furnizor_info['Banca'] : '' ?>">

        <label for="cif_input">CIF</label>
        <input type="text" name="cif_input" id="cif_input" placeholder="Codul de identificare fiscală" required="required" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? $furnizor_info['CIF'] : '' ?>">

        <div class="submit-group">
          <input type="submit" name="submit" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? "Actualizeaza" : "Adauga" ?>" class="buttons">
        </div>
      </form>
    </div>
  </div>
</body>

</html>