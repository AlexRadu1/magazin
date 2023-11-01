<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin']) {
  header("location:../index.php");
}
if (isset($_GET['remove']) && $_GET['remove'] != '') {
  $remove_id = $_GET['remove'];
  mysqli_query($con, "DELETE FROM subcategorii WHERE ID=$remove_id");
  header("location:view_subcategories.php");
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
      <div class="order-detail-header"><a href="manage_subcategories.php">Adauga subcategorie noua</a></div>
      <table id="table_field">
        <thead>
          <th>ID</th>
          <th>Categorie</th>
          <th>Nume</th>
          <th>Actiuni</th>
        </thead>
        <tbody>
          <?php
          $q = mysqli_query($con, "SELECT s.ID,s.denumire AS nume_subcat,c.denumire AS nume_cat  FROM subcategorii s 
          INNER JOIN categorii c ON c.ID=s.cod_categorie");
          while ($row = mysqli_fetch_assoc($q)) {
          ?>
            <tr>
              <td data-title="ID">
                <div class="td-wrapper"><?= $row['ID'] ?></div>
              </td>
              <td data-title="Categorie">
                <div class="td-wrapper"><?= $row['nume_cat'] ?></div>
              </td>
              <td data-title="Nume">
                <div class="td-wrapper"><?= $row['nume_subcat'] ?></div>
              </td>
              <td data-title="Actiuni">
                <div class="td-wrapper">
                  <a href="manage_subcategories.php?id=<?= $row['ID'] ?>" class="buttons">Edit</a>
                  <a href="?remove=<?= $row['ID'] ?>" class="buttons danger" onclick="return confirm('Are you sure you want to delete <?= $row['nume_subcat'] ?>')">Remove</a>
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