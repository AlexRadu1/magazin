<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin']) {
  header("location:../index.php");
}
if (isset($_GET['remove']) && $_GET['remove'] != '') {
  $remove_id = $_GET['remove'];
  mysqli_query($con, "DELETE FROM furnizori WHERE ID=$remove_id");
  header("location:view_providers.php");
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
  <?php include('includes/header.php') ?>
  <div class="wrapper">
    <?php include("includes/sidebar.php") ?>
    <div class="container">
      <div class="order-detail-header"><a href="manage_providers.php">Adauga furnizori</a></div>
      <table id="table_field">
        <thead>
          <th>ID</th>
          <th>Nume</th>
          <th>Adresa</th>
          <th>Judet</th>
          <th>IBAN</th>
          <th>Banca</th>
          <th>CIF</th>
          <th>Actiuni</th>
        </thead>
        <tbody>
          <?php
          $q = mysqli_query($con, "SELECT f.ID,f.denumire AS nume_furnizor,j.denumire AS nume_judet,f.adresa,f.IBAN,f.Banca,f.CIF FROM furnizori f
          INNER JOIN judete j ON j.ID=f.cod_judet");
          while ($row = mysqli_fetch_assoc($q)) {
          ?>
            <tr>
              <td data-title="ID">
                <div class="td-wrapper"><?= $row['ID'] ?></div>
              </td>
              <td data-title="Nume">
                <div class="td-wrapper"><?= $row['nume_furnizor'] ?></div>
              </td>
              <td data-title="Adresa">
                <div class="td-wrapper"><?= $row['adresa'] ?></div>
              </td>
              <td data-title="Judet">
                <div class="td-wrapper"><?= $row['nume_judet'] ?></div>
              </td>
              <td data-title="IBAN">
                <div class="td-wrapper"><?= $row['Banca'] ?></div>
              </td>
              <td data-title="Banca">
                <div class="td-wrapper"><?= $row['IBAN'] ?></div>
              </td>
              <td data-title="CIF">
                <div class="td-wrapper"><?= $row['CIF'] ?></div>
              </td>
              <td data-title="Actiuni">
                <div class="td-wrapper">
                  <a href="manage_providers.php?id=<?= $row['ID'] ?>" class="buttons">Edit</a>
                  <a href="?remove=<?= $row['ID'] ?>" class="buttons danger" onclick="return confirm('Are you sure you want to delete <?= $row['nume_furnizor'] ?>')">Remove</a>
                </div>
              </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
  <script src="javascript.js" defer></script>
</body>

</html>